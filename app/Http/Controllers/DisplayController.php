<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DisplayController extends Controller
{
   public function get_buffer_products()
{
    $buffers = DB::table('buffer')
        ->join('productlist', 'buffer.product_sku', '=', 'productlist.id')
        ->select(
            'buffer.id as buffer_id',
            'buffer.product_sku as sku_id',
            'buffer.buffer_balance_pcs',
            'buffer.created_at',
            'productlist.product_sku',
            'productlist.product_shortname',
            'productlist.product_fullname'
        )
        ->whereIn('buffer.created_at', function ($query) {
            $query->select(DB::raw('MAX(buffer.created_at)'))
                ->from('buffer')
                ->groupBy('product_sku');
        })
        ->orderBy('buffer.product_sku', 'asc')
        ->get();

    return response()->json($buffers);
}


    public function getbufferpcs()
    {
         $getreceivings = DB::table('buffer')
        ->select('id', 'buffer_balance_pcs')
        ->whereIn('id', function ($query) {
            $query->select('id')
                ->from('buffer')
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

       public function addToDisplay(Request $request)
    {
        // Validate request
        $request->validate([
            'sku_id' => 'required|exists:productlist,id|integer',
            'display_pcs_in' => 'required|integer|min:1', // Changed the name to display_pcs_in, for consistency
            'remarks' => 'nullable|string',
            'checker' => 'nullable|string',
        ]);

        $sku_id = $request->sku_id;
        $display_pcs_in = $request->display_pcs_in; // Changed variable name

        // 1. Find the most recent "in" transaction for this SKU in the buffer table
        $lastBufferEntry = DB::table('buffer')
            ->where('product_sku', $sku_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastBufferEntry) {
            return response()->json(['error' => 'No buffer entry found for this SKU.'], 404);
        }

        // Get the current buffer balance for the specified SKU
        $currentBufferBalance = DB::table('buffer')
            ->where('product_sku', $sku_id)
            ->select(DB::raw('SUM(buffer_pcs_in) - SUM(buffer_pcs_out) as balance'))
            ->first()->balance ?? 0;
        
        // Check if there are enough buffer_pcs to deduct
        if ($currentBufferBalance < $display_pcs_in) {
            return response()->json(['error' => 'Insufficient balance in buffer.'], 400);
        }

        // Calculate the new buffer balance
        $newBufferBalance = $currentBufferBalance - $display_pcs_in;

        $now = Carbon::now('Asia/Manila');
        // 2. Create a new "out" record in buffer (to reduce the buffer stock)
        $bufferOutId = DB::table('buffer')->insertGetId([
            'product_sku' => $lastBufferEntry->product_sku,
            'buffer_pcs_in' => 0, // It's an "out" transaction
            'buffer_pcs_out' => $display_pcs_in, // Deducting the amount moved to display
            'buffer_balance_pcs' => $newBufferBalance, // the new buffer balance.
            'checker' => $lastBufferEntry->checker, // Copy checker
            'remarks' => "• Moved {$display_pcs_in} pcs to display. ({$now}) <br> -" . $request->remarks, // New remark
            'created_at' => $now,
            'updated_at' => $now,
        ]);
         // Get the display balance before inserting the new record
        $displayBalance = DB::table('display')
            ->where('product_sku', $sku_id)
            ->select(DB::raw('SUM(display_pcs_in) - SUM(display_pcs_out) as balance'))
            ->first()->balance ?? 0;

        // Calculate the new display balance after adding to display
        $newDisplayBalance = $displayBalance + $display_pcs_in;
        
        // 3. Add a record to the display table
        $displayId = DB::table('display')->insertGetId([
            'product_sku' => $sku_id,
            'display_pcs_in' => $display_pcs_in,
            'display_pcs_out' => 0, // Assuming this is an "in" transaction
            'display_balance_pcs' => $newDisplayBalance,
            'checker' => $request->checker,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Stock added to display successfully.', 'display_id' => $displayId]); //return diplay id
    }
    public function getdisplay()
    {
        $display = DB::table('display')
        ->orderBy('id', 'desc')
        ->get();
        
        return response()->json($display);
    }
    

}



