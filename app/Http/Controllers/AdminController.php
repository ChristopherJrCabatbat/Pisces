<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Message;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount = User::where('role', 'User')->count();
        $deliveryCount = Delivery::count();
        $menuCount = Menu::count();
        $categoryCount = Category::count();

        // Fetch the top 5 most popular menus based on the total order count
        $topPicks = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select(
                'menus.id',
                'menus.name',
                'menus.image',
                'menus.category',
                'menus.price',
                'menus.description',
                DB::raw('SUM(orders.quantity) as total_order_count')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.category', 'menus.price', 'menus.description')
            ->orderByDesc('total_order_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('userCount', 'deliveryCount', 'menuCount', 'categoryCount', 'topPicks'));
    }


    public function menu()
    {
        return view('admin.menu');
    }

    public function delivery()
    {
        return view('admin.delivery');
    }

    public function customers()
    {
        return view('admin.customers');
    }

    public function feedback()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }

        // Fetch all users with the role "User"
        $users = User::where('role', 'User')->get();

        // Fetch the latest message and unread message count for each user
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
        })
            ->sortByDesc(function ($data) {
                return optional($data['latestMessage'])->created_at;
            });

        return view('admin.feedback', compact('userMessages'));
    }


    public function messageUser($userId)
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($userId);

        // Fetch messages between the authenticated user (Admin) and the specified user
        $messages = Message::where(function ($query) use ($userId, $authenticatedUser) {
            $query->where(function ($q) use ($userId, $authenticatedUser) {
                $q->where('user_id', $authenticatedUser->id)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId, $authenticatedUser) {
                $q->where('user_id', $userId)
                    ->where('receiver_id', $authenticatedUser->id);
            });
        })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all unread messages sent by the specified user to the admin as read
        Message::where('user_id', $userId)
            ->where('receiver_id', $authenticatedUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.messageUser', compact('user', 'messages'));
    }





    public function markMessagesAsRead($userId)
    {
        Message::where('user_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->update(['is_read' => true]); // Add is_read column

        return response()->json(['status' => 'success']);
    }



    // public function sendMessage(Request $request, $userId)
    // {
    //     $validated = $request->validate([
    //         'message_text' => 'required|string',
    //     ]);

    //     // Ensure the admin is authenticated
    //     $authUser = Auth::user();
    //     if (!$authUser) {
    //         return response()->json(['error' => 'Admin not authenticated'], 403);
    //     }

    //     // Create the message
    //     Message::create([
    //         'user_id' => $authUser->id, // Admin is the sender
    //         'receiver_id' => $userId, // User is the recipient
    //         'sender_role' => 'Admin',
    //         'message_text' => $validated['message_text'],
    //     ]);

    //     return redirect()->route('admin.messageUser', $userId)->with('success', 'Message sent successfully');
    // }

    public function sendMessage(Request $request, $userId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string',
        ]);

        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Admin not authenticated'], 403);
        }

        // Create the message
        $message = Message::create([
            'user_id' => $authUser->id, // Admin is the sender
            'receiver_id' => $userId, // User is the recipient
            'sender_role' => 'Admin',
            'message_text' => $validated['message_text'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'message_text' => $message->message_text,
                'created_at' => $message->created_at->diffForHumans(),
            ],
        ]);
    }



    public function updates()
    {
        return view('admin.updates');
    }
    public function monitoring()
    {
        return view('admin.monitoring');
    }
}
