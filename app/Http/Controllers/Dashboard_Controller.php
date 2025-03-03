<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard_Controller extends Controller
{
    public function index()
    {
        // Set timezone and get today's date
        $today = Carbon::now('Asia/Manila');

        // Warehouse Metrics (From receivinglist)
        $warehouseTotalIn = DB::table('receivinglist')
            ->whereDate('created_at', $today)
            ->sum('pcs_in');

        $warehouseTotalOut = DB::table('receivinglist')
            ->whereDate('created_at', $today)
            ->sum('pcs_out');

        // Buffer Metrics
        $bufferTotalIn = DB::table('buffer')
            ->whereDate('created_at', $today)
            ->sum('buffer_pcs_in');

        $bufferTotalOut = DB::table('buffer')
            ->whereDate('created_at', $today)
            ->sum('buffer_pcs_out');

        // BO (Back Order) Metric
        $totalBO = DB::table('bo')
            ->sum('bo_balance_pcs');

        // Get inventory data with latest balances
        $inventoryData = DB::table('productlist as p')
            ->select([
                'p.id',
                'p.product_sku',
                DB::raw('COALESCE(w.balance_pcs, 0) as warehouse_qty'),
                DB::raw('COALESCE(b.buffer_balance_pcs, 0) as buffer_qty'),
                DB::raw('COALESCE(d.display_balance_pcs, 0) as display_qty'),
                DB::raw('COALESCE(s.sold_balance_pcs, 0) as sold_qty'),
                DB::raw('COALESCE(bo.bo_balance_pcs, 0) as bo_qty'),
                DB::raw('
                    COALESCE(w.balance_pcs, 0) + 
                    COALESCE(b.buffer_balance_pcs, 0) + 
                    COALESCE(d.display_balance_pcs, 0) + 
                    COALESCE(s.sold_balance_pcs, 0) + 
                    COALESCE(bo.bo_balance_pcs, 0) as total
                ')
            ])
            // Latest warehouse balance subquery
            ->leftJoin(DB::raw('(SELECT sku_id, balance_pcs 
                FROM receivinglist 
                WHERE (sku_id, id) IN (
                    SELECT sku_id, MAX(id) 
                    FROM receivinglist 
                    GROUP BY sku_id
                )
            ) as w'), 'p.id', '=', 'w.sku_id')
            
            // Latest buffer balance subquery
            ->leftJoin(DB::raw('(SELECT product_sku, buffer_balance_pcs 
                FROM buffer 
                WHERE (product_sku, id) IN (
                    SELECT product_sku, MAX(id) 
                    FROM buffer 
                    GROUP BY product_sku
                )
            ) as b'), 'p.id', '=', 'b.product_sku')
            
            // Latest display balance subquery
            ->leftJoin(DB::raw('(SELECT product_sku, display_balance_pcs 
                FROM display 
                WHERE (product_sku, id) IN (
                    SELECT product_sku, MAX(id) 
                    FROM display 
                    GROUP BY product_sku
                )
            ) as d'), 'p.id', '=', 'd.product_sku')
            
            // Latest sold balance subquery
            ->leftJoin(DB::raw('(SELECT product_sku, sold_balance_pcs 
                FROM sold 
                WHERE (product_sku, id) IN (
                    SELECT product_sku, MAX(id) 
                    FROM sold 
                    GROUP BY product_sku
                )
            ) as s'), 'p.id', '=', 's.product_sku')
            
            // Latest BO balance subquery
            ->leftJoin(DB::raw('(SELECT product_sku, bo_balance_pcs 
                FROM bo 
                WHERE (product_sku, id) IN (
                    SELECT product_sku, MAX(id) 
                    FROM bo 
                    GROUP BY product_sku
                )
            ) as bo'), 'p.id', '=', 'bo.product_sku')
            
            ->orderBy('p.product_sku')
            ->get();

        // Calculate total transactions (sum of all inventory totals)
        $totalTransactions = $inventoryData->sum('total');

        return view('dashboard', compact(
            'today',
            'warehouseTotalIn',
            'warehouseTotalOut',
            'bufferTotalIn',
            'bufferTotalOut',
            'totalBO',
            'totalTransactions',
            'inventoryData'
        ));
    }
}