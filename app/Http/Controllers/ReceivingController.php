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
        // Define the mappings
        $monthMap = [
            'A' => '01', 'B' => '02', 'C' => '03', 'D' => '04', 'E' => '05', 'F' => '06',
            'G' => '07', 'H' => '08', 'I' => '09', 'J' => '10', 'K' => '11', 'L' => '12'
        ];

        $yearMap = [
            'A' => '2021', 'B' => '2022', 'C' => '2023', 'D' => '2024', 'E' => '2025',
            'F' => '2026', 'G' => '2027', 'H' => '2028', 'I' => '2029', 'J' => '2030',
            'K' => '2031', 'L' => '2032'
        ];

        $colorMap = [
            'A' => '#007f00', 'B' => '#002f99', 'C' => '#00bfff', 'D' => '#8b4513', 'E' => '#555555',
            'F' => '#cccccc', 'G' => '#ff4500', 'H' => '#ffa500', 'I' => '#d87093', 'J' => '#ff6347',
            'K' => '#4b0082', 'L' => '#228b22'
        ];

        // Fetch records
        $receivings = DB::table('receivinglist')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($receiving) use ($monthMap, $yearMap, $colorMap) {
                // Ensure expiry_date is in MM/YYYY format
                $expiryDate = $receiving->expiry_date;
                $month = substr($expiryDate, 0, 2); // Extract month (e.g., '12')
                $year = substr($expiryDate, 3, 4); // Extract year (e.g., '2023')

                // Find the corresponding letters for month and year
                $monthLetter = array_search($month, $monthMap);
                $yearLetter = array_search($year, $yearMap);

                // Get the color code based on the month letter
                $color = isset($colorMap[$monthLetter]) ? $colorMap[$monthLetter] : '#000'; // Default black if not found
                $colorCode = $monthLetter . $yearLetter;

                // Add the color and color code to the receiving object
                $receiving->color = $color;
                $receiving->color_code = $colorCode;

                return $receiving;
            });

        return response()->json($receivings); // Return JSON response
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
