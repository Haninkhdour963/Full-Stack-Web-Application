<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories including soft deleted ones
        $categories = Category::withTrashed()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('category_icon')->store('category_icon', 'public');

        // Store category data
        $category = Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'category_icon' => $imagePath,
        ]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);
        if ($request->hasFile('category_icon')) {
            // Delete old image
            Storage::delete('public/' . $category->category_icon);
            // Store new image
            $category->category_icon = $request->file('category_icon')->store('category_icon', 'public');
        }

        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->save();

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function softDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true]);
    }
}
