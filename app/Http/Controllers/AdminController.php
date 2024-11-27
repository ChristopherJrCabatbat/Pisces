<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Fetch the latest message for each user
        $userMessages = $users->map(function ($user) {
            $latestMessage = Message::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'user' => $user,
                'latestMessage' => $latestMessage,
            ];
        });

        return view('admin.feedback', compact('userMessages'));
    }

    public function messageUser($userId)
    {
        $user = User::findOrFail($userId);

        // Fetch messages for this user ordered by oldest to newest
        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messageUser', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, $userId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string',
        ]);

        Message::create([
            'user_id' => $userId,
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
