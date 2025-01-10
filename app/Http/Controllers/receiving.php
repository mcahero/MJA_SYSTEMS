<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Receiving extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product-sku-step1' => 'required',
            'transaction-number' => 'required',
            'product-pcs' => 'required|integer|min:0',
            'checker' => 'required',
            'expiry-date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        // Store the data in the database
        // Assuming you have a Receiving model
        $receiving = new Receiving();
        $receiving->sku = $request->input('product-sku-step1');
        $receiving->transaction_number = $request->input('transaction-number');
        $receiving->pcs = $request->input('product-pcs');
        $receiving->checker = $request->input('checker');
        $receiving->expiry_date = $request->input('expiry-date');
        $receiving->remarks = $request->input('remarks');
        $receiving->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Receiving added successfully.');
    }
}