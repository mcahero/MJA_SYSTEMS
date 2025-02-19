<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductsList extends Controller
{
    public function getproducts()
    {
        $products = DB::table('productlist')
         ->orderBy('id', 'desc')
         ->get();

        return view('pages.product_lists', compact('products'));
    }

    public function addproducts(Request $request)
    {
        DB::table('productlist')->insert([
            'product_fullname' => $request->product_fullname,
            'product_shortname' => $request->product_shortname,
            'jda_systemname' => $request->jda_systemname,
            'product_sku' => $request->product_sku,
            'product_barcode' => $request->product_barcode,
            'product_type' => $request->product_type,
            'product_warehouse' => $request->product_warehouse,
            'product_entryperson' => $request->product_entryperson,
            'product_remarks' => $request->product_remarks,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return redirect()->route('product_lists')->with('toast_success', 'Product Added Successfully');
    }
    
    public function deleteproduct($id)
    {
        DB::table('productlist')->where('id', $id)->delete();
        return redirect()->route('product_lists')->with('toast_success', 'Product Deleted Successfully');
    }

    public function editproduct(Request $request)
    {
        DB::table('productlist')->where('id', $request->id)->update([
            'product_fullname' => $request->product_fullname,
            'product_shortname' => $request->product_shortname,
            'jda_systemname' => $request->jda_systemname,
            'product_sku' => $request->product_sku,
            'product_barcode' => $request->product_barcode,
            'product_type' => $request->product_type,
            'product_warehouse' => $request->product_warehouse,
            'product_entryperson' => $request->product_entryperson,
            'product_remarks' => $request->product_remarks,
            'updated_at' => Carbon::now()
        ]);

        return redirect()->route('product_lists')->with('toast_success', 'Product Updated Successfully');
    }

    public function editform()
    {
        $product = DB::table('productlist')->where('id', $id)->first();
        return view('pages.product_lists', compact('product'));
    }

}
