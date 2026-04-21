<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Show expenses page
     */
    public function index()
    {
        return view('expenses');
    }

    /**
     * Get all expenses
     */
    public function getAll()
    {
        $expenses = Expense::orderBy('date', 'desc')->get();
        
        $statistics = [
            'total_payment' => Expense::where('transaction_type', 'payment')->sum('amount'),
            'total_receive' => Expense::where('transaction_type', 'receive')->sum('amount'),
            'total_expense' => Expense::where('transaction_type', 'expense')->sum('amount'),
            'total_deposit' => Expense::where('transaction_type', 'deposit')->sum('amount'),
        ];

        return response()->json([
            'expenses' => $expenses,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Store a new expense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:payment,receive,expense,deposit',
            'date' => 'required|date',
            'vendor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,bank,cheque,card',
            'description' => 'required|string',
        ]);

        $expense = Expense::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction saved successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * Update an expense
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:payment,receive,expense,deposit',
            'date' => 'required|date',
            'vendor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,bank,cheque,card',
            'description' => 'required|string',
        ]);

        $expense->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * Delete an expense
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully',
        ]);
    }
}
