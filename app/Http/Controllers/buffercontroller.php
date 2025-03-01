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

    public function getbuffer()
    {
        $buffer = DB::table('buffer')
        ->orderBy('id', 'desc')
        ->get();
        
        return response()->json($buffer);
    }

        // ✅ Add product stock to buffer and update warehouse stock
        public function addToBuffer(Request $request)
    {
        \Log::info('Received request:', $request->all()); // Log the incoming request

        // Validate request
        $request->validate([
            'warehouse_pcs' => 'required|integer|min:0',
            'sku_id' => 'required|exists:productlist,id|integer',
            'pcs' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'checker' => 'nullable|string',
        ]);

        // Retrieve the current warehouse stock
        $warehouseStock = DB::table('receivinglist')
            ->where('sku_id', $request->sku_id)
            ->value('pcs');

        if ($warehouseStock === null) {
            return response()->json(['error' => 'Warehouse stock not found.'], 404);
        }

        if ($warehouseStock < $request->pcs) {
            return response()->json(['error' => 'Not enough stock in warehouse.'], 400);
        }

        // Subtract the pcs from warehouse_pcs
        $newStock = $warehouseStock - $request->pcs;

        // Update warehouse stock
        DB::table('receivinglist')
            ->where('sku_id', $request->sku_id)
            ->update(['pcs' => $newStock]);

        // Insert into buffer
        $bufferId = DB::table('buffer')->insertGetId([
            'warehouse_pcs' => $newStock, // Updated stock value
            'product_sku' => $request->sku_id,
            'pcs' => $request->pcs,
            'checker' => $request->checker,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Stock added to buffer successfully.', 'buffer_id' => $bufferId, 'new_stock' => $newStock]);
    }

}

