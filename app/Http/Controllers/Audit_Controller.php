<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Audit_Controller extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page', 1); // Get the page number, default to 1
        $perPage = 10; // Number of results per page

        $query = DB::table('receivinglist')
            ->join('productlist', 'receivinglist.sku_id', '=', 'productlist.id')
            ->select('productlist.id', 'productlist.product_sku', 'productlist.product_fullname')
            ->distinct();

        if ($term) {
            $query->where('product_sku', 'LIKE', "%$term%");
        }

        $results = $query->paginate($perPage, ['*'], 'page', $page); // Paginate the results

        return response()->json($results);
    }

    public function getSystemCounts(Request $request)
    {
        $skuId = $request->input('sku_id');

        // Check if skuId is provided, otherwise return an error or default response
        if (!$skuId) {
            return response()->json(['error' => 'SKU ID is required.'], 400);
        }

        $counts = [
            'warehouse' => DB::table('receivinglist')
                ->where('sku_id', $skuId)
                ->orderByDesc('id')
                ->value('balance_pcs') ?? 0,

            'buffer' => DB::table('buffer')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('buffer_balance_pcs') ?? 0,

            'display' => DB::table('display')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('display_balance_pcs') ?? 0,

            'sold' => DB::table('sold')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('sold_balance_pcs') ?? 0,
        ];

        return response()->json([
            'warehouse' => number_format($counts['warehouse']),
            'buffer' => number_format($counts['buffer']),
            'display' => number_format($counts['display']),
            'sold' => number_format($counts['sold'])
        ]);
    }
}
