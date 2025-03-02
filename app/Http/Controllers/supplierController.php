<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class supplierController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('suppliers')->get();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|unique:suppliers|max:255',
        ]);

        $supplierId = DB::table('suppliers')->insertGetId([
            'supplier_name' => $request->supplier_name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $supplier = DB::table('suppliers')->find($supplierId);

        return response()->json($supplier, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $supplier = DB::table('suppliers')->find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $id . '|max:255',
        ]);

        $existingSupplier = DB::table('suppliers')->find($id);

        if (!$existingSupplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        DB::table('suppliers')
            ->where('id', $id)
            ->update([
                'supplier_name' => $request->supplier_name,
                'updated_at' => now(),
            ]);

        $supplier = DB::table('suppliers')->find($id);
        return response()->json($supplier);
    }

    public function destroy($id)
    {
        $existingSupplier = DB::table('suppliers')->find($id);

        if (!$existingSupplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        DB::table('suppliers')->where('id', $id)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
