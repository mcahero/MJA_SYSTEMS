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
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = DB::table('receivinglist')
            ->join('productlist', 'receivinglist.sku_id', '=', 'productlist.id')
            ->select('productlist.id', 'productlist.product_sku', 'productlist.product_fullname')
            ->distinct();

        if ($term) {
            $query->where('product_sku', 'LIKE', "%$term%");
        }

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($results);
    }

    public function getSystemCounts(Request $request)
    {
        $skuId = $request->input('sku_id');

        if (!$skuId) {
            return response()->json(['error' => 'SKU ID is required.'], 400);
        }

        $counts = [
            //since receiving and warehouse are the same, you can just get it from here.
            'receivinglist' => DB::table('receivinglist')
                ->where('sku_id', $skuId)
                ->select(DB::raw('SUM(pcs_in) - SUM(pcs_out) as balance'))
                ->first()->balance ?? 0,

            //now remove the warehouse and combine it to receiving list.

            'buffer' => DB::table('buffer')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('buffer_balance_pcs') ?? 0,

            'display' => DB::table('display')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('display_balance_pcs') ?? 0,

            'bo' => DB::table('bo')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('bo_balance_pcs') ?? 0,

            'sold' => DB::table('sold')
                ->where('product_sku', $skuId)
                ->orderByDesc('id')
                ->value('sold_balance_pcs') ?? 0,
        ];

        return response()->json([
            'receivinglist' => number_format($counts['receivinglist']), //this is now receiving list + warehouse.
            //'warehouse' => number_format($counts['warehouse']), //remove the warehouse since its the same as receivinglist.
            'buffer' => number_format($counts['buffer']),
            'display' => number_format($counts['display']),
            'bo' => number_format($counts['bo']),
            'sold' => number_format($counts['sold'])
        ]);
    }
}
