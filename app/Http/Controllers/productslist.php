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

    public function displayproductlist()
    {
        $products = DB::table('productlist')
         ->orderBy('id', 'asc')
         ->get();

        return $products;
    }

    public function addproducts(Request $request)
    {
        $validatedData = $request->validate([
            'product_fullname' => 'required|string|max:255',
            'product_sku' => 'required|string|max:255',
            'product_barcode' => 'required|string|max:255',
            'product_type' => 'required|string',
            'product_entryperson' => 'required|string|max:255',
        ]);

        DB::table('productlist')->insert([
            'product_fullname' => $validatedData['product_fullname'],
            'product_shortname' => $request->product_shortname ?? null,
            'jda_systemname' => $request->jda_systemname ?? null,
            'product_sku' => $validatedData['product_sku'],
            'product_barcode' => $validatedData['product_barcode'],
            'product_type' => $validatedData['product_type'],
            'product_warehouse' => $request->product_warehouse ?? null,
            'product_entryperson' => $validatedData['product_entryperson'],
            'product_remarks' => $request->product_remarks ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Product Added Successfully'], 200);
    }

    public function deleteproduct($id)
{
    $deleted = DB::table('productlist')->where('id', $id)->delete();

    if ($deleted) {
        return response()->json([
            'status' => 'success',
            'message' => 'Product Deleted Successfully'
        ], 200);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Product Not Found'
        ], 404);
    }
}



    // public function editproduct(Request $request)
    // {
    //     DB::table('productlist')->where('id', $request->product_id)->update([
    //         'product_fullname' => $request->product_fullname,
    //         'product_shortname' => $request->product_shortname,
    //         'jda_systemname' => $request->jda_systemname,
    //         'product_sku' => $request->product_sku,
    //         'product_barcode' => $request->product_barcode,
    //         'product_type' => $request->product_type,
    //         'product_warehouse' => $request->product_warehouse,
    //         'product_entryperson' => $request->product_entryperson,
    //         'product_remarks' => $request->product_remarks,
    //         'updated_at' => Carbon::now()
    //     ]);
    // }

    public function editform(Request $request)
    {
        $id = $request->id;
        $product = DB::table('productlist')->where('id', $id)->first();
        return response()->json($product);
    }

}
