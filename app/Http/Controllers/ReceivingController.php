<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Product;
use Carbon\Carbon;

class ReceivingController extends Controller
{
    public function index()
{
    $receivings = Receiving::with('product')->get();
    
    $products = Product::all()->map(function ($product) {
        $product->created_at = Carbon::parse($product->created_at)->format('m/d/Y');
        $product->updated_at = Carbon::parse($product->updated_at)->format('m/d/Y');
        return $product;
    });

    return view('pages.receiving', compact('receivings', 'products'));
}

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_sku_step1' => 'required|exists:productlist,id',
            'transaction_number' => 'required|string|max:255',
            'product_pcs' => 'required|integer|min:0',
            'checker' => 'required|string|max:255',
            'date_input' => 'required|regex:/^(0[1-9]|1[0-2])\/\d{4}$/', // Validate MM/YYYY format
            'remarks' => 'nullable|string',
        ]);

        // Store the data in the database
      $receiving = new Receiving();
        $receiving->sku_id = $request->input('product_sku_step1');
        $receiving->transaction_number = $request->input('transaction_number');
        $receiving->pcs = $request->input('product_pcs');
        $receiving->checker = $request->input('checker');
        $receiving->expiry_date = $request->input('date_input');
        $receiving->remarks = $request->input('remarks');
        $receiving->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Receiving added successfully.');
    }
}
