<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // get all Categories
    public function index()
    {

        return response([
            'categories' => Category::orderBy('created_at', 'desc')->with('senders')
                ->withCount('senders')
                ->get()
        ], 200);
    }

    // get single Category
    public function show($id)
    {
        return response([
            'category' => Category::where('id', $id)->with('senders')
                ->withCount('senders')->get()
        ], 200);
    }

    // create a Category
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::firstOrCreate([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'Category created.',
            'category' => $category,
        ], 200);
    }

    // update a Category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response([
                'message' => 'Category not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $category->update([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'Category updated.',
            'category' => $category
        ], 200);
    }

    //delete Category
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response([
                'message' => 'Category not found.'
            ], 403);
        }

        $category->delete();

        return response([
            'message' => 'Category deleted.'
        ], 200);
    }
}
