<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Delivery;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Count deliveries with specified statuses
        $deliveryBadgeCount = Delivery::whereIn('status', [
            'Pending GCash Transaction',
            'Pending',
            'Preparing',
            'Out for Delivery'
        ])->count();

        // Fetch search, filter, and sort parameters
        $filter = $request->input('filter', '');

        // Query categories with filtering, searching, and sorting
        $categories = Category::query()
            // ->when($search, function ($query, $search) {
            //     $query->where('category', 'like', '%' . $search . '%'); // Apply search filter
            // })
            ->when($filter, function ($query, $filter) {
                $query->orderBy('category', $filter); // Apply sorting filter
            })
            ->get(); // Retrieve all categories without pagination

        // Pass variables to the view
        return view('admin.category', compact('categories', 'filter', 'totalUnreadCount', 'deliveryBadgeCount'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Count deliveries with specified statuses
        $deliveryBadgeCount = Delivery::whereIn('status', [
            'Pending GCash Transaction',
            'Pending',
            'Preparing',
            'Out for Delivery'
        ])->count();

        return view('admin.categoryCreate', compact('totalUnreadCount', 'deliveryBadgeCount'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'category' => 'required|string', // Add validation for category
            'image' => 'image|mimes:jpeg,png,jpg,gif', // Validate image file type and size
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
    public function show(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch the category by ID
        $category = Category::findOrFail($id);

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Count deliveries with specified statuses
        $deliveryBadgeCount = Delivery::whereIn('status', [
            'Pending GCash Transaction',
            'Pending',
            'Preparing',
            'Out for Delivery'
        ])->count();

        // Pass both the category and unread message count to the view
        return view('admin.categoryShow', compact('category', 'totalUnreadCount', 'deliveryBadgeCount'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        $category = Category::findOrFail($id);

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Count deliveries with specified statuses
        $deliveryBadgeCount = Delivery::whereIn('status', [
            'Pending GCash Transaction',
            'Pending',
            'Preparing',
            'Out for Delivery'
        ])->count();

        return view('admin.categoryEdit', compact('category', 'totalUnreadCount', 'deliveryBadgeCount'));
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
            'image' => 'image|mimes:jpeg,png,jpg,gif', // Validate image type and size
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
