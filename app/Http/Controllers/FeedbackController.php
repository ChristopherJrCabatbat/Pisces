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
use App\Models\Message;

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

    //     $user = Auth::user(); // Get the currently authenticated user

    //     // Check for existing feedback
    //     $existingFeedback = Feedback::where('customer_name', $user->first_name . ' ' . $user->last_name)
    //         ->where('menu_items', $validated['menu_items'])
    //         ->first();

    //     if ($existingFeedback) {
    //         $existingFeedback->update([
    //             'feedback_text' => $validated['feedback_text'],
    //             'rating' => $validated['rating'],
    //         ]);
    //         session()->flash('toast', ['message' => 'Feedback updated successfully!', 'type' => 'success']);
    //     } else {
    //         $orderNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    //         Feedback::create([
    //             'customer_name' => $user->first_name . ' ' . $user->last_name,
    //             'order_number' => $orderNumber,
    //             'menu_items' => $validated['menu_items'],
    //             'feedback_text' => $validated['feedback_text'],
    //             'rating' => $validated['rating'],
    //         ]);
    //         session()->flash('toast', ['message' => 'Feedback submitted successfully!', 'type' => 'success']);
    //     }

    //     // Update menu's rating
    //     $menu = \App\Models\Menu::where('name', $validated['menu_items'])->first();
    //     if ($menu) {
    //         $menu->rating = round($validated['rating'], 1);
    //         $menu->save();
    //     }

    //     // Format the message
    //     $messageText = sprintf(
    //         "I would like to share my feedback on '%s'. \"%s\" I rate it %d star%s.",
    //         $validated['menu_items'],
    //         ucfirst($validated['feedback_text']),
    //         $validated['rating'],
    //         $validated['rating'] > 1 ? 's' : ''
    //     );

    //     // Send the formatted message to the admin (userId: 1 as an example)
    //     Message::create([
    //         'user_id' => $user->id,
    //         'receiver_id' => 1, // Admin or target user ID
    //         'sender_role' => 'User',
    //         'message_text' => $messageText,
    //     ]);

    //     // Redirect back
    //     return redirect()->back();
    // }

    public function store(Request $request)
    {
        // Validate input fields
        $validated = $request->validate([
            'feedback_text' => 'nullable|string|max:1000',
            'rating' => 'required|numeric|min:1|max:5',
            'menu_items' => 'required|string',
        ]);

        $user = Auth::user(); // Get the currently authenticated user

        // Check for existing feedback
        $existingFeedback = Feedback::where('customer_name', $user->first_name . ' ' . $user->last_name)
            ->where('menu_items', $validated['menu_items'])
            ->first();

        if ($existingFeedback) {
            $existingFeedback->update([
                'feedback_text' => $validated['feedback_text'],
                'rating' => $validated['rating'],
            ]);
            session()->flash('toast', ['message' => 'Feedback updated successfully!', 'type' => 'success']);
        } else {
            $orderNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Feedback::create([
                'customer_name' => $user->first_name . ' ' . $user->last_name,
                'order_number' => $orderNumber,
                'menu_items' => $validated['menu_items'],
                'feedback_text' => $validated['feedback_text'],
                'rating' => $validated['rating'],
            ]);
            session()->flash('toast', ['message' => 'Feedback submitted successfully!', 'type' => 'success']);
        }

        // Update menu's rating
        $menu = \App\Models\Menu::where('name', $validated['menu_items'])->first();
        if ($menu) {
            $menu->rating = round($validated['rating'], 1);
            $menu->save();
        }

        // Format the message
        $feedbackText = $validated['feedback_text'] ? sprintf(' "%s"', ucfirst($validated['feedback_text'])) : '';
        $messageText = sprintf(
            "I would like to share my feedback on '%s'.%s I rate it %d star%s.",
            $validated['menu_items'],
            $feedbackText,
            $validated['rating'],
            $validated['rating'] > 1 ? 's' : ''
        );

        // Send the formatted message to the admin (userId: 1 as an example)
        Message::create([
            'user_id' => $user->id,
            'receiver_id' => 1, // Admin or target user ID
            'sender_role' => 'User',
            'message_text' => $messageText,
        ]);

        // Redirect back
        return redirect()->back();
    }




    public function updateSentiment(Request $request, $id)
    {
        // Validate the new sentiment
        $validatedData = $request->validate([
            'sentiment' => 'required|string|in:Positive,Negative',
        ]);

        // Find the feedback by ID and update the sentiment
        $feedback = Feedback::findOrFail($id);
        $feedback->sentiment = $validatedData['sentiment'];

        if ($feedback->save()) {
            $message = "Feedback sentiment changed to {$validatedData['sentiment']}.";

            // Return a JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            // Add success message to session for non-AJAX requests
            session()->flash('toast', [
                'message' => $message,
                'type' => 'success',
            ]);
        } else {
            $message = 'Failed to update feedback sentiment.';

            // Return a JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ]);
            }

            // Add error message to session for non-AJAX requests
            session()->flash('toast', [
                'message' => $message,
                'type' => 'error',
            ]);
        }

        // Redirect back for non-AJAX requests
        return redirect()->back();
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
