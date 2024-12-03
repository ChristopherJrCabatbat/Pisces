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
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the uploaded image in the 'public/category_images' directory
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        // Create a new category entry in the database
        try {
            Category::create([
                'category' => $validated['category'], // Store the category
                'image' => $imagePath, // Save image path
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Category added successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to add category item. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        // Redirect back to the category index page
        return redirect()->route('admin.category.index');
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

        $imagePath = null;

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

        try {
            // Update the category with new data
            $category->update([
                'category' => $newCategoryName, // Update category name
                'image' => $imagePath ?? $category->image, // Update image path only if a new image is uploaded
            ]);

            // Update all menus to reflect the new category name
            if ($oldCategoryName !== $newCategoryName) {
                DB::table('menus')->where('category', $oldCategoryName)->update(['category' => $newCategoryName]);
            }

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Category updated successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to update category. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        // Redirect back to the category index page
        return redirect()->route('admin.category.index');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        try {
            // Delete the category
            $category->delete();

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Category deleted successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to delete category. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        // Redirect back to the category index page
        return redirect()->route('admin.category.index');
    }
}
