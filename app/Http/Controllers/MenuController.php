<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // $menus = Menu::paginate(2);
        $menus = Menu::all();

        $categories = DB::table('categories')->select('category')->distinct()->get();

        return view('admin.menu', compact('menus', 'categories'));
    }

    public function menuSearch(Request $request)
    {
        // // Get the search query from the request
        // $search = $request->input('search');

        // // Check if a search query exists
        // if ($search) {
        //     // Search the menus by name, category, price, or description
        //     $menus = Menu::where('name', 'LIKE', '%' . $search . '%')
        //         ->orWhere('category', 'LIKE', '%' . $search . '%')
        //         ->orWhere('description', 'LIKE', '%' . $search . '%')
        //         ->orWhere('price', 'LIKE', '%' . $search . '%')
        //         ->paginate(2); // Adjust pagination size if needed
        // } else {
        //     // No search query, return all menus paginated
        //     $menus = Menu::paginate(2); // Adjust pagination size if needed
        // }

        // // If the request is AJAX, return only the table rows
        // if ($request->ajax()) {
        //     return view('admin.partials.menu_table_body', compact('menus'))->render();
        // }



        // // Return the full view with the menus
        // return view('admin.menu', compact('menus', 'search')); // Pass search query for reuse in the view
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // Fetch all categories from the database
        return view('admin.menuCreate', compact('categories'));
    }

    public function menuCreateCategory()
    {
        $categories = Category::all(); // Fetch all categories

        return view('admin.menuCreateCategory', compact( 'categories'));
    }

    public function menuEditCategory(string $id)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories

        return view('admin.menuEdit', compact('menus', 'categories'));
    }

    public function menuUpdateCategory(Request $request, string $id)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories
        return view('admin.menuEdit', compact('menus', 'categories'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string', // Add validation for category
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file type and size
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Store the uploaded image in the 'public/menu_images' directory
            $imagePath = $request->file('image')->store('menu_images', 'public');
        }

        // Create a new menu entry in the database
        Menu::create([
            'name' => $validated['name'],
            'category' => $validated['category'], // Store the category
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagePath ?? null, // Save image path
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.menu.index')->with('success', 'Menu item added successfully.');
    }

    public function storeCategory(Request $request)
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
        return redirect()->route('admin.menu.index')->with('success', 'Category item added successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menus = Menu::findOrFail($id);
        return view('admin.menuShow', compact('menus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories

        return view('admin.menuEdit', compact('menus', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Fetch the existing menu item
        $menu = Menu::findOrFail($id);

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => '|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is optional on update
        ]);

        // Handle file upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($menu->image) {
                Storage::delete('public/' . $menu->image);
            }

            // Store the new image in 'public/menu_images' directory
            $imagePath = $request->file('image')->store('menu_images', 'public');
            $menu->image = $imagePath; // Update the image path
        }

        // Update the menu entry with new values
        $menu->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $menu->image ?? $menu->image, // Keep the existing image if no new one is uploaded
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted successfully.');
    }
}
