<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(Request $request)
    // {
    //     // Validate input fields
    //     $validated = $request->validate([
    //         'feedback_text' => 'nullable|string|max:1000',
    //         'rating' => 'required|numeric|min:1|max:5',
    //         'menu_items' => 'required|string',
    //     ]);

    //     /** @var \App\Models\User $user */
    //     $user = Auth::user(); // Get the currently authenticated user

    //     // Generate a random 6-digit order number
    //     $orderNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    //     // Create the feedback entry
    //     Feedback::create([
    //         'customer_name' => $user->first_name . ' ' . $user->last_name, // Combine first and last name
    //         'order_number' => $orderNumber,
    //         'menu_items' => $validated['menu_items'], // Value from hidden input
    //         'feedback_text' => $validated['feedback_text'],
    //         'rating' => $validated['rating'],
    //     ]);

    //     // Update the menu's rating in the menus table
    //     $menu = \App\Models\Menu::where('name', $validated['menu_items'])->first();

    //     if ($menu) {
    //         // Calculate the new average rating
    //         $averageRating = Feedback::where('menu_items', $menu->name)
    //             ->avg('rating');

    //         $menu->rating = round($averageRating, 1); // Round to 1 decimal place
    //         $menu->save(); // Save the updated rating
    //     }

    //     // Redirect back with success message
    //     return redirect()->back()->with('success', 'Feedback submitted successfully!');
    // }

    public function store(Request $request)
{
    // Validate input fields
    $validated = $request->validate([
        'feedback_text' => 'nullable|string|max:1000',
        'rating' => 'required|numeric|min:1|max:5',
        'menu_items' => 'required|string',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user(); // Get the currently authenticated user

    // Check if feedback already exists for this user and menu item
    $existingFeedback = Feedback::where('customer_name', $user->first_name . ' ' . $user->last_name)
        ->where('menu_items', $validated['menu_items'])
        ->first();

    if ($existingFeedback) {
        // Update the existing feedback
        $existingFeedback->update([
            'feedback_text' => $validated['feedback_text'],
            'rating' => $validated['rating'],
        ]);
    } else {
        // Generate a random 6-digit order number for new feedback
        $orderNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create a new feedback entry
        Feedback::create([
            'customer_name' => $user->first_name . ' ' . $user->last_name, // Combine first and last name
            'order_number' => $orderNumber,
            'menu_items' => $validated['menu_items'], // Value from hidden input
            'feedback_text' => $validated['feedback_text'],
            'rating' => $validated['rating'],
        ]);
    }

    // Update the menu's rating in the menus table
    $menu = \App\Models\Menu::where('name', $validated['menu_items'])->first();

    if ($menu) {
        // Use the latest rating submitted by the user
        $menu->rating = round($validated['rating'], 1); // Update to 1 decimal place
        $menu->save();
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Feedback submitted successfully!');
}






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
