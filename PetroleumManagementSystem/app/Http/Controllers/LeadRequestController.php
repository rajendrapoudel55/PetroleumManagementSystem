<?php

namespace App\Http\Controllers;

use App\Models\LeadRequest;
use Illuminate\Http\Request;

class LeadRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255|regex:/^[A-Za-z0-9][A-Za-z0-9\s\.\&\'\-\,]*$/',
            'name' => 'required|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'phone_number' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:500',
        ]);

        LeadRequest::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Details saved successfully.',
            ]);
        }

        return back()->with('success', 'Details saved successfully.');
    }
}