<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request)
    {
        // Validate input fields
        $validated = $request->validate([
            'feedback_text' => 'nullable|string|max:1000',
            'rating' => 'required|numeric|min:1|max:5',
            'order_number' => 'required|string|max:255', // Ensure order_number is passed
        ]);

        /** @var User $user */
        $user = Auth::user(); // Get the currently authenticated user

        // Create the feedback entry
        Feedback::create([
            'customer_name' => $user->name, // Use the authenticated user's name
            'order_number' => $validated['order_number'], // Use the validated order number
            'menu_items' => json_encode($request->menu_items ?? []), // Handle menu items gracefully
            'feedback_text' => $validated['feedback_text'], // Use the feedback text
            'rating' => $validated['rating'], // Use the rating value
        ]);

        // Return a success response
        return response()->json(['message' => 'Feedback submitted successfully!']);
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
