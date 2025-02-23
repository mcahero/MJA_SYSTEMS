<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BufferController extends Controller
{
    // ✅ Fetch all warehouse products
    public function getWarehouseProducts()
    {
        $products = DB::table('receivinglist')->get();
        return response()->json($products);
    }

    // ✅ Add product stock to buffer and update warehouse stock
    public function addToBuffer(Request $request)
    {
        // Validate request
        $request->validate([
            'sku' => 'required|string',
            'pcs' => 'required|integer|min:1',
            'product_id' => 'required|integer'
        ]);

        // Find product
        $product = DB::table('receivinglist')->where('id', $request->product_id)->first();

        if (!$product || $product->pcs < $request->pcs) {
            return response()->json(['message' => 'Insufficient stock!'], 400);
        }

        // Deduct from warehouse
        DB::table('receivinglist')->where('id', $request->product_id)->decrement('pcs', $request->pcs);

        // Add to buffer
        DB::table('buffer')->insert([
            'sku' => $request->sku,
            'pcs' => $request->pcs,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Stock added to buffer!',
            'new_warehouse_pcs' => $product->pcs - $request->pcs
        ]);
    }
}

