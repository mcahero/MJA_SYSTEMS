<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BufferController extends Controller
{
    // ✅ Fetch all warehouse products
    public function getWarehouseProducts()
{
    $receivings = DB::table('receivinglist')
        ->join('productlist', 'receivinglist.sku_id', '=', 'productlist.id')
        ->select(
            'receivinglist.id as receiving_id', // Add aliases to avoid column name conflicts
            'receivinglist.sku_id',
            'receivinglist.balance_pcs',
            'receivinglist.created_at', // Assuming you have a created_at or updated_at column
            'productlist.product_sku',
            'productlist.product_shortname',
            'productlist.product_fullname'
        )
        ->whereIn('receivinglist.id', function ($query) {
            $query->select(DB::raw('MAX(receivinglist.id)')) // Get the maximum id for each sku_id
                ->from('receivinglist')
                ->groupBy('sku_id');
        })
        ->orderBy('receivinglist.sku_id', 'asc')
        ->get();

    return response()->json($receivings);
}

    public function getwareproducts()
    {
         $getreceivings = DB::table('receivinglist')
        ->select('id', 'balance_pcs')
        ->whereIn('id', function ($query) {
            $query->select('id')
                ->from('receivinglist')
                ->groupBy('id');
        })
        ->orderBy('id', 'asc')
        ->orderBy('created_at', 'desc') // Assuming you have a 'created_at' or similar timestamp
        ->get()
        ->groupBy('id')
        ->map(function ($items) {
            return $items->first();
        })
        ->values();

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
        // Validate request
        $request->validate([
            'sku_id' => 'required|exists:productlist,id|integer',
            'buffer_pcs_in' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'checker' => 'nullable|string',
        ]);
        $sku_id = $request->sku_id;
        $buffer_pcs_in = $request->buffer_pcs_in;

        // 1. Find the most recent "in" transaction for this SKU
        $lastReceiving = DB::table('receivinglist')
            ->where('sku_id', $sku_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastReceiving) {
            return response()->json(['error' => 'No receiving history found for this SKU.'], 404);
        }

        // Get the current balance for the specified SKU
        $currentBalance = DB::table('receivinglist')
            ->where('sku_id', $sku_id)
            ->select(DB::raw('SUM(pcs_in) - SUM(pcs_out) as balance'))
            ->first()->balance ?? 0;

        // Check if there are enough balance_pcs to deduct
        if ($currentBalance < $buffer_pcs_in) {
            return response()->json(['error' => 'Insufficient balance in receiving list.'], 400);
        }
        
        // Calculate the new balance
        $newBalance = $currentBalance - $buffer_pcs_in;
        $now = Carbon::now('Asia/Manila');
        // 2. Create a new "out" record in receivinglist (copy details from the last "in")
        $receivingId = DB::table('receivinglist')->insertGetId([
            'sku_id' => $lastReceiving->sku_id,
            'transaction_number' => 'TRN-BUFFER-OUT-' . $now->format('YmdHis'), // New transaction number
            'pcs_in' => 0, // It's an "out" transaction
            'pcs_out' => $buffer_pcs_in,
            'balance_pcs' => $newBalance, // the new balance.
            'checker' => $lastReceiving->checker, // Copy checker
            'expiry_date' => $lastReceiving->expiry_date, // Copy expiry date
            'remarks' => "• Moved {$buffer_pcs_in} pcs to Buffer. ({$now}) <br> -" . $request->remarks, // New remark
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Get the buffer balance before inserting the new record
        $bufferBalance = DB::table('buffer')
            ->where('product_sku', $sku_id)
            ->select(DB::raw('SUM(buffer_pcs_in) - SUM(buffer_pcs_out) as balance'))
            ->first()->balance ?? 0;

        // Calculate the new buffer balance after adding to buffer
        $newBufferBalance = $bufferBalance + $buffer_pcs_in;
        
        $now = Carbon::now('Asia/Manila');
        // Insert into buffer table
        $bufferId = DB::table('buffer')->insertGetId([
            'product_sku' => $sku_id,
            'buffer_pcs_in' => $buffer_pcs_in,
            'buffer_pcs_out' => 0, // Assuming this is an "in" transaction
            'buffer_balance_pcs' => $newBufferBalance,
            'checker' => $request->checker,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Stock added to buffer successfully.', 'buffer_id' => $bufferId]);
    }   
    public function get_buffer_balance($sku_id)
    {
        $latestBuffer = DB::table('buffer')
            ->where('product_sku', $sku_id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'buffer_balance' => $latestBuffer ? $latestBuffer->buffer_balance_pcs : 0
        ]);
    }

}

