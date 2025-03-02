<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Sold_Controller extends Controller
{
     public function get_display_products()
        {
            $solds = DB::table('display')
                ->join('productlist', 'display.product_sku', '=', 'productlist.id')
                ->select(
                    'display.id as buffer_id',
                    'display.product_sku as sku_id',
                    'display.display_balance_pcs',
                    'display.created_at',
                    'productlist.product_sku',
                    'productlist.product_shortname',
                    'productlist.product_fullname'
                )
                ->whereIn('display.id', function ($query) {
                    $query->select(DB::raw('MAX(display.id)'))
                        ->from('display')
                        ->groupBy('product_sku');
                })
                ->orderBy('display.product_sku', 'asc')
                ->get();

            return response()->json($solds);
        }
        public function get_display_pcs()
        {
            $getreceivings = DB::table('display')
            ->select('id', 'display_balance_pcs')
            ->whereIn('id', function ($query) {
                $query->select('id')
                    ->from('display')
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

        public function addToSold(Request $request)
        {
            // Validate request
            $request->validate([
                'sku_id' => 'required|exists:productlist,id|integer',
                'sold_pcs_in' => 'required|integer|min:1', // Changed the name to sold_pcs_in, for consistency
                'remarks' => 'nullable|string',
                'checker' => 'nullable|string',
            ]);

            $sku_id = $request->sku_id;
            $sold_pcs_in = $request->sold_pcs_in; // Changed variable name

            // 1. Find the most recent "in" transaction for this SKU in the buffer table
            $lastsoldEntry = DB::table('display')
                ->where('product_sku', $sku_id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$lastsoldEntry) {
                return response()->json(['error' => 'No Display entry found for this SKU.'], 404);
            }

            // Get the current display balance for the specified SKU
            $currentsoldBalance = DB::table('display')
                ->where('product_sku', $sku_id)
                ->select(DB::raw('SUM(display_pcs_in) - SUM(display_pcs_out) as balance'))
                ->first()->balance ?? 0;
            
            // Check if there are enough buffer_pcs to deduct
            if ($currentsoldBalance < $sold_pcs_in) {
                return response()->json(['error' => 'Insufficient balance in Display.'], 400);
            }

            // Calculate the new display balance
            $newsoldBalance = $currentsoldBalance - $sold_pcs_in;

            $now = Carbon::now('Asia/Manila');
            // 2. Create a new "out" record in display (to reduce the display stock)
            $displayOutId = DB::table('display')->insertGetId([
                'product_sku' => $lastsoldEntry->product_sku,
                'display_pcs_in' => 0, // It's an "out" transaction
                'display_pcs_out' => $sold_pcs_in, // Deducting the amount moved to display
                'display_balance_pcs' => $newsoldBalance, // the new buffer balance.
                'checker' => $lastsoldEntry->checker, // Copy checker
                'remarks' => "• Moved {$sold_pcs_in} pcs to Sold ({$now}) <br> -" . $request->remarks, // New remark
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // Get the sold balance before inserting the new record
            $soldBalance = DB::table('sold')
                ->where('product_sku', $sku_id)
                ->select(DB::raw('SUM(sold_pcs_in) - SUM(sold_pcs_out) as balance'))
                ->first()->balance ?? 0;

            // Calculate the new sold balance after adding to sold
            $newsoldBalance = $soldBalance + $sold_pcs_in;
            
            // 3. Add a record to the display table
            $now = Carbon::now('Asia/Manila');
            $soldId = DB::table('sold')->insertGetId([
                'product_sku' => $sku_id,
                'sold_pcs_in' => $sold_pcs_in,
                'sold_pcs_out' => 0, // Assuming this is an "in" transaction
                'sold_balance_pcs' => $newsoldBalance,
                'checker' => $request->checker,
                'remarks' => $request->remarks,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => 'Stock added to display successfully.', 'display_id' => $soldId]); //return diplay id
        }
        public function getsold()
        {
            $sold = DB::table('sold')
            ->orderBy('id', 'desc')
            ->get();
            
            return response()->json($sold);
        }

        public function get_sold_balance($sku_id)
        {
            $latestSold = DB::table('sold')
                ->where('product_sku', $sku_id)
                ->orderBy('id', 'desc')
                ->first();

            return response()->json([
                'sold_balance' => $latestSold ? $latestSold->sold_balance_pcs : 0
            ]);
        }
}
