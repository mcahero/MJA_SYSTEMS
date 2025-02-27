<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
    public function index()
    {
        return view('pages.receiving');
    }

    public function getreceiving()
    {
        $receivings = DB::table('receivinglist')
            ->orderBy('id', 'desc')
            ->get();

         return $receivings;
    }


    public function getproducts(Request $request)
    {
        $products = DB::table('productlist')->get();
        return $products;
    }
    public function addreceiving(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
            'sku_id' => 'required|exists:productlist,id|integer',
                'transaction_number' => 'required|string|max:255',
                'pcs' => 'required|integer|min:1',
                'checker' => 'required|string|max:255',
                'remarks' => 'nullable|string|max:255',
                'expiry_date' => 'required|date_format:m/Y',
            ]);

            // Use transaction for database operations
            DB::beginTransaction();
            try {
                // Insert using Query Builder
                $receivingId = DB::table('receivinglist')->insertGetId([
                    'sku_id' => $validatedData['sku_id'],
                    'transaction_number' => $validatedData['transaction_number'],
                    'pcs' => $validatedData['pcs'],
                    'checker' => $validatedData['checker'],
                    'remarks' => $validatedData['remarks'],
                    'expiry_date' => $validatedData['expiry_date'],
                    'created_at' => now(),
                    'updated_at' => now(),

                ]);

                $createdReceiving = DB::table('receivinglist')
                    ->where('id', $receivingId)
                    ->first();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Receiving added successfully',
                    'receiving' => $createdReceiving
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add receiving',
                    'error' => $e->getMessage()
                ], 500);

            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
