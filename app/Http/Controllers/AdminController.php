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
        // Fetch all users with role "User"
        $users = User::where('role', 'User')->get();

        // Fetch the latest message for each user and sort them by the time of the latest message
        $userMessages = $users->map(function ($user) {
            $latestMessage = Message::where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'user' => $user,
                'latestMessage' => $latestMessage,
            ];
        })->sortByDesc(function ($data) {
            return optional($data['latestMessage'])->created_at;
        });

        return view('admin.feedback', compact('userMessages'));
    }




    public function messageUser($userId)
    {
        $user = User::findOrFail($userId);

        // Fetch messages between Admin and the user
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        return view('admin.messageUser', compact('user', 'messages'));
    }

    public function markMessagesAsRead($userId)
    {
        Message::where('user_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->update(['is_read' => true]); // Add is_read column

        return response()->json(['status' => 'success']);
    }



    public function sendMessage(Request $request, $userId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string',
        ]);

        // Ensure the admin is authenticated
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Admin not authenticated'], 403);
        }

        // Create the message
        Message::create([
            'user_id' => $authUser->id, // Admin is the sender
            'receiver_id' => $userId, // User is the recipient
            'sender_role' => 'Admin',
            'message_text' => $validated['message_text'],
        ]);

        return redirect()->route('admin.messageUser', $userId)->with('success', 'Message sent successfully');
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
