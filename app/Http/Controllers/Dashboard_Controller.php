<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard_Controller extends Controller
{
    public function index()
    {
        // Today's date in Manila timezone
        $today = Carbon::now('Asia/Manila');
        
        // 1. Today's Transactions Data
        $warehouseTotalIn = DB::table('receivinglist')
            ->whereDate('created_at', $today)
            ->sum('pcs_in');

        $warehouseTotalOut = DB::table('receivinglist')
            ->whereDate('created_at', $today)
            ->sum('pcs_out');

        $totalBO = DB::table('bo')
            ->sum('bo_balance_pcs');

        // 2. SKU Count Data
        $inventoryData = DB::table('productlist as p')
            ->select([
                'p.id',
                'p.product_sku',
                DB::raw('COALESCE(SUM(r.balance_pcs), 0) as warehouse_qty'),
                DB::raw('COALESCE(SUM(b.buffer_balance_pcs), 0) as buffer_qty'),
                DB::raw('COALESCE(SUM(d.display_balance_pcs), 0) as display_qty'),
                DB::raw('COALESCE(SUM(s.sold_balance_pcs), 0) as sold_qty'),
                DB::raw('COALESCE(SUM(bo.bo_balance_pcs), 0) as bo_qty'),
                DB::raw('COALESCE(
                    SUM(r.balance_pcs) + 
                    SUM(b.buffer_balance_pcs) + 
                    SUM(d.display_balance_pcs) + 
                    SUM(s.sold_balance_pcs) + 
                    SUM(bo.bo_balance_pcs), 0) as total')
            ])
            ->leftJoin('receivinglist as r', function($join) {
                $join->on('p.id', '=', 'r.sku_id')
                    ->whereNull('r.deleted_at');
            })
            ->leftJoin('buffer as b', 'p.id', '=', 'b.product_sku')
            ->leftJoin('display as d', 'p.id', '=', 'd.product_sku')
            ->leftJoin('sold as s', 'p.id', '=', 's.product_sku')
            ->leftJoin('bo', 'p.id', '=', 'bo.product_sku')
            ->groupBy('p.id', 'p.product_sku')
            ->orderBy('p.product_sku')
            ->get();

        return view('dashboard', compact(
            'warehouseTotalIn',
            'warehouseTotalOut',
            'totalBO',
            'inventoryData',
            'today'
        ));
    }
}