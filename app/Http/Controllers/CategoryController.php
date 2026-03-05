<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'true',
            'massages' => 'category retrieved successfully',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'status' => 'true',
            'massages' => 'category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'false',
                'massages' => 'category not found'
            ], 404);
        }

        return response()->json([
            'status' => 'true',
            'massages' => 'category retrieved successfully',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string'
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'false',
                'massages' => 'category not found'
            ], 404);
        }

        $category->update($request->all());

        return response()->json([
            'status' => 'true',
            'massages' => 'category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'false',
                'massages' => 'category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'true',
            'massages' => 'category deleted successfully'
        ]);
    }
}
