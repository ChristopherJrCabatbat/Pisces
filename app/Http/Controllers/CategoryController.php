<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('admin.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categoryCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'category' => 'required|string', // Add validation for category
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file type and size
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Store the uploaded image in the 'public/menu_images' directory
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        // Create a new menu entry in the database
        Category::create([
            'category' => $validated['category'], // Store the category
            'image' => $imagePath ?? null, // Save image path
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.category.index')->with('success', 'Category item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categoryShow', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categoryEdit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Validate the form data
        $validated = $request->validate([
            'category' => 'required|string', // Validate category name
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
        ]);

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image && Storage::exists('public/' . $category->image)) {
                Storage::delete('public/' . $category->image);
            }

            // Store the new image in the 'public/category_images' directory
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        // Check if the category name is being updated
        $oldCategoryName = $category->category;
        $newCategoryName = $validated['category'];

        // Update the category with new data
        $category->update([
            'category' => $newCategoryName, // Update category name
            'image' => $imagePath ?? $category->image, // Update image path only if a new image is uploaded
        ]);

        // Update all menus to reflect the new category name
        if ($oldCategoryName !== $newCategoryName) {
            DB::table('menus')->where('category', $oldCategoryName)->update(['category' => $newCategoryName]);
        }

        // Redirect back with a success message
        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Menu item deleted successfully.');
    }
}
