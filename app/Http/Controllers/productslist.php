<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductsList extends Controller
{
    public function index()
    {
        $products = Product::all()->map(function ($product) {
            $product->created_at = Carbon::parse($product->created_at)->format('m/d/Y');
            $product->updated_at = Carbon::parse($product->updated_at)->format('m/d/Y');
            return $product;
        });
        return view('pages.product_lists', compact('products'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product-sku-name' => 'required|string|max:255',
            'product-short-name' => 'string|max:255',
            'jda-system-name' => 'string|max:255',
            'product-sku' => 'required|string|max:255',
            'product-barcode' => 'required|string|max:255',
            'product-type' => 'required|string',
            'product-warehouse' => 'required|string|max:255',
            'product-entryperson' => 'nullable|string|max:255',
            'product-remarks' => 'nullable|string',
        ]);

        // Store the product data
        // Assuming you have a Product model
        Product::create([
            'product_fullname' => $request->input('product-sku-name'),
            'product_shortname' => $request->input('product-short-name'),
            'jda_systemname' => $request->input('jda-system-name'),
            'product_sku' => $request->input('product-sku'),
            'product_barcode' => $request->input('product-barcode'),
            'product_type' => $request->input('product-type'),
            'product_warehouse' => $request->input('product-warehouse'),
            'product_entryperson' => $request->input('product-entryperson'),
            'product_remarks' => $request->input('product-remarks'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product added successfully!');
    }
}
