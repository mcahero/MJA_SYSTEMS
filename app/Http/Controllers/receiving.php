<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Product;

class ReceivingController extends Controller
{
    public function index()
    {
        $receivings = Receiving::with('sku')->get();
        return view('receivings.index', compact('receivings'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'sku_id' => 'required|exists:products,id',
            'transaction_number' => 'required|string|max:255',
            'pcs' => 'required|integer',
            'checker' => 'required|string|max:255',
            'expiry_date' => 'required|regex:/^(0[1-9]|1[0-2])\/\d{4}$/', // MM/YYYY format
            'remarks' => 'nullable|string',
        ]);

        // Store the receiving data
        Receiving::create($request->all());

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Receiving added successfully!');
    }
}
