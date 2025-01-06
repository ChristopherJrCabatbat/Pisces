<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UnreadMessagesController extends Controller
{
    /**
     * Fetch unread message counts and latest messages for users.
     *
     * @return array
     */
    public function getUnreadMessageData()
    {
        $authenticatedUser = Auth::user(); // Current authenticated admin
    
        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }
    
        // Fetch users with the role "User"
        $users = User::where('role', 'User')->get();
    
        // Map user messages with unread counts
        $userMessages = $users->map(function ($user) use ($authenticatedUser) {
            $latestMessage = Message::where(function ($query) use ($user, $authenticatedUser) {
                $query->where(function ($q) use ($user, $authenticatedUser) {
                    $q->where('user_id', $authenticatedUser->id)
                        ->where('receiver_id', $user->id);
                })->orWhere(function ($q) use ($user, $authenticatedUser) {
                    $q->where('user_id', $user->id)
                        ->where('receiver_id', $authenticatedUser->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();
    
            // Count unread messages sent by the user to the admin
            $unreadCount = Message::where('user_id', $user->id)
                ->where('receiver_id', $authenticatedUser->id)
                ->where('is_read', false)
                ->count();
    
            return [
                'user' => $user,
                'latestMessage' => $latestMessage,
                'unreadCount' => $unreadCount,
            ];
        });
    
        // Calculate the total unread count for users with the role "User"
        $totalUnreadCount = $userMessages->sum('unreadCount');
    
        return [
            'userMessages' => $userMessages,
            'totalUnreadCount' => $totalUnreadCount,
        ];
    }
    
}