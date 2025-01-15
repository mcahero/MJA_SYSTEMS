<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
    public function index()
    {
        // Map month and year values
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

        // Fetch receivings and calculate color code for each
        $receivings = Receiving::with('product')->get()->map(function($receiving) use ($monthMap, $yearMap, $colorMap) {
            // Assuming expiry_date is in MM/YYYY format (e.g., 12/2023)
            $expiryDate = $receiving->expiry_date;
            $month = substr($expiryDate, 0, 2); // Extract month (e.g., '12')
            $year = substr($expiryDate, 3, 4); // Extract year (e.g., '2023')

            // Find the corresponding letters for month and year
            $monthLetter = array_search($month, $monthMap);
            $yearLetter = array_search($year, $yearMap);

            // Get the color code based on the month letter
            $color = isset($colorMap[$monthLetter]) ? $colorMap[$monthLetter] : '#000'; // Default to black if not found
            $colorCode = $monthLetter . $yearLetter;

            // Add the color and color code to the receiving object
            $receiving->color_code = $colorCode;
            $receiving->color = $color;

            return $receiving;
        });

        // Fetch products
        $products = Product::all()->map(function ($product) {
            $product->created_at = Carbon::parse($product->created_at)->format('m/d/Y');
            $product->updated_at = Carbon::parse($product->updated_at)->format('m/d/Y');
            return $product;
        });

        return view('pages.receiving', compact('receivings', 'products'));
    }

    // Store method remains the same as before
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'product_sku_step1' => 'required|exists:productlist,id',
                'transaction_number' => 'required|string|max:255',
                'product_pcs' => 'required|integer|min:0',
                'checker' => 'required|string|max:255',
                'date_input' => 'required',
                'remarks' => 'nullable|string',
            ]);

            // Store the data in the database
            $receiving = new Receiving();
            $receiving->sku_id = $request->input('product_sku_step1');
            $receiving->transaction_number = $request->input('transaction_number');
            $receiving->pcs = $request->input('product_pcs');
            $receiving->checker = $request->input('checker');
            $receiving->expiry_date = $request->input('date_input');
            $receiving->remarks = $request->input('remarks');
            $receiving->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Receiving added successfully.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error storing receiving: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
