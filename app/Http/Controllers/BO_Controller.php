<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BO_Controller extends Controller
{
        public function subtractFromBO(Request $request)
        {
            $request->validate([
                'sku_id' => 'required|exists:productlist,id',
                'bo_pcs_out' => 'required|integer|min:1',
                'remarks' => 'nullable|string',
                'checker' => 'nullable|string',
            ]);

            $skuId = $request->sku_id;
            $boPcsOut = $request->bo_pcs_out;

            // Get current balance
            $currentBalance = DB::table('bo')
                ->where('product_sku', $skuId)
                ->selectRaw('SUM(bo_pcs_in) - SUM(bo_pcs_out) as balance')
                ->first()->balance ?? 0;

            if ($currentBalance < $boPcsOut) {
                return response()->json(['message' => 'Insufficient B.O balance'], 400);
            }

            $newBalance = $currentBalance - $boPcsOut;

            $now = Carbon::now('Asia/Manila');
            DB::table('bo')->insert([
                'product_sku' => $skuId,
                'bo_pcs_out' => $boPcsOut,
                'bo_balance_pcs' => $newBalance,
                'remarks' => $request->remarks,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => true]);
        }
        public function takeOutFromBO(Request $request)
        {
            // Validate request
            $request->validate([
                'sku_id' => 'required|exists:productlist,id|integer',
                'bo_pcs_out' => 'required|integer|min:1',
                'remarks' => 'nullable|string',
            ]);

            $sku_id = $request->sku_id;
            $bo_pcs_out = $request->bo_pcs_out;

            // Get the current BO balance
            $currentBOBalance = DB::table('bo')
                ->where('product_sku', $sku_id)
                ->select(DB::raw('SUM(bo_pcs_in) - SUM(bo_pcs_out) as balance'))
                ->first()->balance ?? 0;

            // Check if there are enough PCS to take out
            if ($currentBOBalance < $bo_pcs_out) {
                return response()->json(['error' => 'Insufficient balance in B.O.'], 400);
            }

            // Calculate the new BO balance
            $newBOBalance = $currentBOBalance - $bo_pcs_out;

            // Create a new "out" record in the bo table
            $now = Carbon::now('Asia/Manila');
            DB::table('bo')->insert([
                'product_sku' => $sku_id,
                'bo_pcs_in' => 0,
                'bo_pcs_out' => $bo_pcs_out,
                'bo_balance_pcs' => $newBOBalance,
                'remarks' =>  "• Moved {$bo_pcs_out} pcs taken out from B.O.({$now}) <br> -" . $request->remarks,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => 'PCS taken out from B.O. successfully.']);
        }
        public function get_display_products()
        {
            $bos = DB::table('display')
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

            return response()->json($bos);
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

        public function addToBO(Request $request)
        {
            // Validate request
            $request->validate([
                'sku_id' => 'required|exists:productlist,id|integer',
                'bo_pcs_in' => 'required|integer|min:1', // Changed the name to bo_pcs_in, for consistency
                'remarks' => 'nullable|string',
                'checker' => 'nullable|string',
            ]);

            $sku_id = $request->sku_id;
            $bo_pcs_in = $request->bo_pcs_in; // Changed variable name

            // 1. Find the most recent "in" transaction for this SKU in the buffer table
            $lastBOEntry = DB::table('display')
                ->where('product_sku', $sku_id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$lastBOEntry) {
                return response()->json(['error' => 'No Display entry found for this SKU.'], 404);
            }

            // Get the current display balance for the specified SKU
            $currentBOBalance = DB::table('display')
                ->where('product_sku', $sku_id)
                ->select(DB::raw('SUM(display_pcs_in) - SUM(display_pcs_out) as balance'))
                ->first()->balance ?? 0;
            
            // Check if there are enough buffer_pcs to deduct
            if ($currentBOBalance < $bo_pcs_in) {
                return response()->json(['error' => 'Insufficient balance in Display.'], 400);
            }

            // Calculate the new display balance
            $newBOBalance = $currentBOBalance - $bo_pcs_in;

            $now = Carbon::now('Asia/Manila');
            // 2. Create a new "out" record in display (to reduce the display stock)
            $displayOutId = DB::table('display')->insertGetId([
                'product_sku' => $lastBOEntry->product_sku,
                'display_pcs_in' => 0, // It's an "out" transaction
                'display_pcs_out' => $bo_pcs_in, // Deducting the amount moved to display
                'display_balance_pcs' => $newBOBalance, // the new buffer balance.
                'checker' => $lastBOEntry->checker, // Copy checker
                'remarks' => "• Moved {$bo_pcs_in} pcs to B.O. ({$now}) <br> -" . $request->remarks, // New remark
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // Get the bo balance before inserting the new record
            $BOBalance = DB::table('bo')
                ->where('product_sku', $sku_id)
                ->select(DB::raw('SUM(bo_pcs_in) - SUM(bo_pcs_out) as balance'))
                ->first()->balance ?? 0;

            // Calculate the new bo balance after adding to bo
            $newBOBalance = $BOBalance + $bo_pcs_in;
            
            // 3. Add a record to the display table
            $now = Carbon::now('Asia/Manila');
            $BOId = DB::table('bo')->insertGetId([
                'product_sku' => $sku_id,
                'bo_pcs_in' => $bo_pcs_in,
                'bo_pcs_out' => 0, // Assuming this is an "in" transaction
                'bo_balance_pcs' => $newBOBalance,
                'checker' => $request->checker,
                'remarks' => $request->remarks,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => 'Stock added to display successfully.', 'display_id' => $BOId]); //return diplay id
        }
        public function getBO()
        {
            $bo = DB::table('bo')
            ->orderBy('id', 'desc')
            ->get();
            
            return response()->json($bo);
        }
        public function get_bo_balance($sku_id)
        {
            $latestBO = DB::table('bo')
                ->where('product_sku', $sku_id)
                ->orderBy('id', 'desc')
                ->first();

            return response()->json([
                'bo_balance' => $latestBO ? $latestBO->bo_balance_pcs : 0
            ]);
        }
    

}