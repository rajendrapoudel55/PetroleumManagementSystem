<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KhaltiPaymentController extends Controller
{
    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'purchase_order_id' => 'required|string|max:255',
            'purchase_order_name' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'customer_phone' => 'nullable|regex:/^[0-9]{10}$/',
        ]);

        $secretKey = config('services.khalti.secret_key');
        if (!$secretKey) {
            return response()->json([
                'isOk' => false,
                'error' => 'Khalti secret key is not configured.',
            ], 422);
        }

        $amountInPaisa = (int) round(((float) $validated['amount']) * 100);

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
            'return_url' => url('/taxinvoice'),
            'website_url' => url('/taxinvoice'),
            'amount' => $amountInPaisa,
            'purchase_order_id' => $validated['purchase_order_id'],
            'purchase_order_name' => $validated['purchase_order_name'],
            'customer_info' => [
                'name' => $validated['customer_name'] ?? 'Walk-in Customer',
                'phone' => $validated['customer_phone'] ?? '',
            ],
        ]);

        if (!$response->successful()) {
            return response()->json([
                'isOk' => false,
                'error' => $response->json('detail') ?: 'Unable to initiate Khalti payment.',
                'raw' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'isOk' => true,
            'data' => $response->json(),
        ]);
    }

    public function lookup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pidx' => 'required|string',
        ]);

        $secretKey = config('services.khalti.secret_key');
        if (!$secretKey) {
            return response()->json([
                'isOk' => false,
                'error' => 'Khalti secret key is not configured.',
            ], 422);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post('https://a.khalti.com/api/v2/epayment/lookup/', [
            'pidx' => $validated['pidx'],
        ]);

        if (!$response->successful()) {
            return response()->json([
                'isOk' => false,
                'error' => $response->json('detail') ?: 'Unable to verify Khalti payment.',
                'raw' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'isOk' => true,
            'data' => $response->json(),
        ]);
    }
}
