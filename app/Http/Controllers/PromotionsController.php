<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Promotion;

class PromotionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Fetch search, filter, and sort parameters
        $filter = $request->input('filter', ''); // Sorting direction (asc/desc)

        // Query categories with filtering, searching, and sorting
        $promotions = Promotion::query()
            ->when($filter, function ($query, $filter) {
                $query->orderBy('name', $filter);
            })
            ->get();

        // Pass variables to the view
        return view('admin.promotions', compact('promotions', 'filter', 'totalUnreadCount'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        return view('admin.promotionsCreate', compact('totalUnreadCount'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif', // Validate image file type and size
            'how_often' => 'required', // Validate image file type and size
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotion_images', 'public');
        }

        try {
            Promotion::create([
                'name' => $validated['name'],
                'image' => $imagePath, // Save image path
                'how_often' => $validated['how_often'],
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Promotion added successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to add promotion. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        return redirect()->route('admin.promotions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch the category by ID
        $promotion = Promotion::findOrFail($id);

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Pass both the category and unread message count to the view
        return view('admin.promotionsShow', compact('promotion', 'totalUnreadCount'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        $promotion = Promotion::findOrFail($id);

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        return view('admin.promotionsEdit', compact('promotion', 'totalUnreadCount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $promotions = Promotion::findOrFail($id);

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif', // Validate image type and size
            'how_often' => 'required',
        ]);

        $imagePath = null;

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($promotions->image && Storage::exists('public/' . $promotions->image)) {
                Storage::delete('public/' . $promotions->image);
            }

            $imagePath = $request->file('image')->store('promotion_images', 'public');
        }

        // $oldCategoryName = $promotions->category;
        // $newPromotionName = $validated['category'];

        try {
            $promotions->update([
                'name' => $validated['name'],
                'image' => $imagePath ?? $promotions->image, // Update image path only if a new image is uploaded
                'how_often' => $validated['how_often'],
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Promotion updated successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to update promotion. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        return redirect()->route('admin.promotions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promotion = Promotion::findOrFail($id);

        try {
            // Delete the category
            $promotion->delete();

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Promotion deleted successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to delete promotion. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        // Redirect back to the category index page
        return redirect()->route('admin.promotions.index');
    }
}
