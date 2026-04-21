<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    private function stockSellingPrice(Stock $stock): float
    {
        // Keep backward compatibility before the selling_price column exists.
        if (Schema::hasColumn('stocks', 'selling_price') && $stock->selling_price !== null) {
            return (float) $stock->selling_price;
        }

        return round((float) $stock->unit_price * 1.15, 2);
    }

    /**
     * Get all products - combining both products and stocks tables
     */
    public function index()
    {
        try {
            // Get products from stocks table (fuel inventory)
            $stocks = Stock::all()->map(function ($stock) {
                return [
                    '__backendId' => 'stock_' . $stock->id,
                    '__type' => 'stock',
                    '__stockId' => $stock->id,
                    'product_name' => $stock->fuel_type,
                    'sku' => $stock->fuel_code,
                    'category' => 'Fuel',
                    'stock_quantity' => (float)$stock->current_quantity,
                    'unit' => 'ltr',
                    'cost_price' => (float)$stock->unit_price,
                    'selling_price' => $this->stockSellingPrice($stock),
                    'min_stock' => 500,
                    'last_purchase' => $stock->updated_at,
                    'created_at' => $stock->created_at,
                    'updated_at' => $stock->updated_at,
                ];
            });

            // Get other products from products table (non-fuel)
            $products = Product::all()->map(function ($product) {
                return [
                    '__backendId' => 'prod_' . $product->id,
                    '__type' => 'product',
                    '__productId' => $product->id,
                    'product_name' => $product->product_name,
                    'sku' => $product->sku,
                    'category' => $product->category,
                    'stock_quantity' => (float)$product->stock_quantity,
                    'unit' => $product->unit,
                    'cost_price' => (float)$product->cost_price,
                    'selling_price' => (float)$product->selling_price,
                    'min_stock' => (float)$product->min_stock,
                    'last_purchase' => $product->last_purchase,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });

            // Merge as plain collections because both sets are transformed into arrays.
            $allItems = collect($stocks->all())
                ->merge($products->all())
                ->values();

            return response()->json([
                'isOk' => true,
                'data' => $allItems->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'sku' => 'required|string',
                'category' => 'nullable|string',
                'stock_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'min_stock' => 'nullable|numeric|min:0',
                'last_purchase' => 'nullable|date',
            ]);

            // If it's a fuel product, save to stocks table
            if (strtolower((string) ($validated['category'] ?? '')) === 'fuel') {
                $hasStockSellingPrice = Schema::hasColumn('stocks', 'selling_price');
                $stock = Stock::firstOrCreate(
                    ['fuel_code' => $validated['sku']],
                    [
                        'fuel_type' => $validated['product_name'],
                        'current_quantity' => $validated['stock_quantity'],
                        'unit_price' => $validated['cost_price'],
                        'total_value' => $validated['stock_quantity'] * $validated['cost_price'],
                    ]
                );

                if ($hasStockSellingPrice) {
                    $stock->selling_price = $validated['selling_price'];
                }
                $stock->save();

                return response()->json([
                    'isOk' => true,
                    'data' => [
                        '__backendId' => 'stock_' . $stock->id,
                        '__type' => 'stock',
                        'product_name' => $stock->fuel_type,
                        'sku' => $stock->fuel_code,
                        'category' => 'Fuel',
                        'stock_quantity' => (float)$stock->current_quantity,
                        'unit' => 'ltr',
                        'cost_price' => (float)$stock->unit_price,
                        'selling_price' => $this->stockSellingPrice($stock),
                        'min_stock' => 500,
                        'last_purchase' => $stock->updated_at,
                        'created_at' => $stock->created_at,
                        'updated_at' => $stock->updated_at,
                    ]
                ], 201);
            } else {
                // Save to products table for non-fuel items
                $product = Product::create($validated);

                return response()->json([
                    'isOk' => true,
                    'data' => [
                        '__backendId' => 'prod_' . $product->id,
                        '__type' => 'product',
                        'product_name' => $product->product_name,
                        'sku' => $product->sku,
                        'category' => $product->category,
                        'stock_quantity' => (float)$product->stock_quantity,
                        'unit' => $product->unit,
                        'cost_price' => (float)$product->cost_price,
                        'selling_price' => (float)$product->selling_price,
                        'min_stock' => (float)$product->min_stock,
                        'last_purchase' => $product->last_purchase,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
                    ]
                ], 201);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->errors()
                ]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Update a product
     */
    public function update(Request $request, $id)
    {
        try {
            // Parse ID to determine which table
            if (strpos($id, 'stock_') === 0) {
                $stockId = str_replace('stock_', '', $id);
                $stock = Stock::findOrFail($stockId);

                $validated = $request->validate([
                    'product_name' => 'required|string|max:255',
                    'sku' => 'required|string',
                    'category' => 'nullable|string',
                    'stock_quantity' => 'required|numeric|min:0',
                    'unit' => 'required|string',
                    'cost_price' => 'required|numeric|min:0',
                    'selling_price' => 'required|numeric|min:0',
                    'min_stock' => 'nullable|numeric|min:0',
                    'last_purchase' => 'nullable|date',
                ]);

                $hasStockSellingPrice = Schema::hasColumn('stocks', 'selling_price');
                $updatePayload = [
                    'fuel_type' => $validated['product_name'],
                    'fuel_code' => $validated['sku'],
                    'current_quantity' => $validated['stock_quantity'],
                    'unit_price' => $validated['cost_price'],
                    'total_value' => $validated['stock_quantity'] * $validated['cost_price'],
                ];

                if ($hasStockSellingPrice) {
                    $updatePayload['selling_price'] = $validated['selling_price'];
                }

                $stock->update($updatePayload);

                return response()->json([
                    'isOk' => true,
                    'data' => [
                        '__backendId' => 'stock_' . $stock->id,
                        '__type' => 'stock',
                        'product_name' => $stock->fuel_type,
                        'sku' => $stock->fuel_code,
                        'category' => 'Fuel',
                        'stock_quantity' => (float)$stock->current_quantity,
                        'unit' => 'ltr',
                        'cost_price' => (float)$stock->unit_price,
                        'selling_price' => $this->stockSellingPrice($stock),
                        'min_stock' => 500,
                        'last_purchase' => $stock->updated_at,
                        'created_at' => $stock->created_at,
                        'updated_at' => $stock->updated_at,
                    ]
                ]);
            } else {
                $productId = str_replace('prod_', '', $id);
                $product = Product::findOrFail($productId);

                $validated = $request->validate([
                    'product_name' => 'required|string|max:255',
                    'sku' => 'required|string|unique:products,sku,' . $productId,
                    'category' => 'nullable|string',
                    'stock_quantity' => 'required|numeric|min:0',
                    'unit' => 'required|string',
                    'cost_price' => 'required|numeric|min:0',
                    'selling_price' => 'required|numeric|min:0',
                    'min_stock' => 'nullable|numeric|min:0',
                    'last_purchase' => 'nullable|date',
                ]);

                $product->update($validated);

                return response()->json([
                    'isOk' => true,
                    'data' => [
                        '__backendId' => 'prod_' . $product->id,
                        '__type' => 'product',
                        'product_name' => $product->product_name,
                        'sku' => $product->sku,
                        'category' => $product->category,
                        'stock_quantity' => (float)$product->stock_quantity,
                        'unit' => $product->unit,
                        'cost_price' => (float)$product->cost_price,
                        'selling_price' => (float)$product->selling_price,
                        'min_stock' => (float)$product->min_stock,
                        'last_purchase' => $product->last_purchase,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
                    ]
                ]);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => 'Product not found'
                ]
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->errors()
                ]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        try {
            if (strpos($id, 'stock_') === 0) {
                $stockId = str_replace('stock_', '', $id);
                $stock = Stock::findOrFail($stockId);
                $stock->delete();
            } else {
                $productId = str_replace('prod_', '', $id);
                $product = Product::findOrFail($productId);
                $product->delete();
            }

            return response()->json([
                'isOk' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => 'Product not found'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'isOk' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }
}
