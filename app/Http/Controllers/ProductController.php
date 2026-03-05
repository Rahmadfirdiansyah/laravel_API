<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 'true',
            'massages' => 'data retrieved successfully',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'status' => 'true',
            'massages' => 'data created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'false',
                'massages' => 'data not found'
            ], 404);
        }

        return response()->json([
            'status' => 'true',
            'massages' => 'data retrieved successfully',
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|integer'
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'false',
                'massages' => 'data not found'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'status' => 'true',
            'massages' => 'data updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'false',
                'massages' => 'data not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => 'true',
            'massages' => 'data deleted successfully'
        ]);
    }
}
