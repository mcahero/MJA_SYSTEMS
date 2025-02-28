<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BufferController extends Controller
{
    // ✅ Fetch all warehouse products
    public function getWarehouseProducts()
    {
        $receivings = DB::table('receivinglist')
            ->join('productlist', 'receivinglist.sku_id', '=', 'productlist.id') // Ensure correct FK relationship
            ->select(
                'receivinglist.sku_id as id', // This is necessary for the JS dropdown
                'productlist.product_sku', 
                'productlist.product_fullname', 
                'productlist.product_shortname'
            )
            ->groupBy('receivinglist.sku_id', 'productlist.product_sku', 'productlist.product_fullname', 'productlist.product_shortname') // Avoid duplicates
            ->get();

        return response()->json($receivings);
    }

    public function getwareproducts()
    {
        $getreceivings = DB::table('receivinglist')->get();
        return response()->json($getreceivings);
    }


    public function getproducts()
    {
        $products = DB::table('productlist')->get();
        return response()->json($products);
    }

    // ✅ Add product stock to buffer and update warehouse stock
    public function addToBuffer(Request $request)
    {
        \Log::info('Received request:', $request->all()); // Log the incoming request

        // Validate request
        $request->validate([
            'receivinglist' => 'required|exists:warehouse,id|integer',
            'sku_id' => 'required|exists:productlist,id|integer',
            'pcs' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'checker' => 'nullable|string', 

        ]);

        $bufferId = DB::table('buffer')->insertGetId([
            'receivinglist' => $request->receivinglist,
            'product_sku' => $request->sku_id,
            'pcs' => $request->pcs,
            'checker' => $request->checker, 
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}

