<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class buffercontroller extends Controller
{
    public function getbuffer( Request $request)
    {
        $products = DB::table('productlist')
         ->orderBy('id', 'desc')
         ->get();

        if ($request->ajax()) {
        return response()->json($products); // Return JSON response for AJAX
        }

        return view('/pages/buffer', compact('products'));
    }

}
