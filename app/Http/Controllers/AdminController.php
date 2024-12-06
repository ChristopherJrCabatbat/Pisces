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
use App\Models\Feedback;

class AdminController extends Controller
{
    // public function dashboard()
    // {
    //     $userCount = User::where('role', 'User')->count();
    //     $deliveryCount = Delivery::count();
    //     $menuCount = Menu::count();
    //     $categoryCount = Category::count();

    //     // Fetch the top 5 most popular menus based on the total order count
    //     $topPicks = DB::table('orders')
    //         ->join('menus', 'orders.menu_name', '=', 'menus.name')
    //         ->select(
    //             'menus.id',
    //             'menus.name',
    //             'menus.image',
    //             'menus.category',
    //             'menus.price',
    //             'menus.description',
    //             DB::raw('SUM(orders.quantity) as total_order_count')
    //         )
    //         ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.category', 'menus.price', 'menus.description')
    //         ->orderByDesc('total_order_count')
    //         ->take(5)
    //         ->get();

    //     return view('admin.dashboard', compact('userCount', 'deliveryCount', 'menuCount', 'categoryCount', 'topPicks'));
    // }

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

    // Calculate total sales for each month (only 'Delivered' orders)
    $monthlySales = Delivery::select(
        DB::raw("SUM(total_price) as total_sales"),
        DB::raw("MONTHNAME(created_at) as month_name")
    )
        ->where('status', 'Delivered')
        ->groupBy(DB::raw("MONTH(created_at)"), DB::raw("MONTHNAME(created_at)"))
        ->orderBy(DB::raw("MONTH(created_at)"))
        ->pluck('total_sales', 'month_name');

    return view('admin.dashboard', compact(
        'userCount',
        'deliveryCount',
        'menuCount',
        'categoryCount',
        'topPicks',
        'monthlySales'
    ));
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
        $feedbacks = Feedback::all();

        return view('admin.feedback', compact('feedbacks'));
    }

    public function respondFeedback(Request $request)
{
    $feedback = Feedback::findOrFail($request->feedback_id);
    $feedback->response = $request->response;
    $feedback->save();

    // Set a toast session with the success message
    session()->flash('toast', [
        'message' => 'Response has been submitted successfully.',
        'type' => 'success', // 'success' or 'error'
    ]);

    return redirect()->back();
}






    public function customerMessages()
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

        return view('admin.customerMessages', compact('userMessages'));
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
        // Fetch only users with the 'User' role
        $users = User::where('role', 'User')->get();
        return view('admin.updates', compact('users'));
    }

    public function viewOrders($userId)
    {
        // Fetch user by ID
        $user = User::findOrFail($userId);

        // Fetch deliveries linked to the user via email
        $deliveries = Delivery::where('email', $user->email)->with('orders')->get();

        // Process each delivery to find the menu image with the highest quantity
        $deliveriesWithImages = $deliveries->map(function ($delivery) {
            $orders = $delivery->orders;

            if ($orders->isNotEmpty()) {
                // Get the order with the highest quantity
                $highestQuantityOrder = $orders->sortByDesc('quantity')->first();

                if ($highestQuantityOrder) {
                    // Get the menu item associated with the highest quantity order
                    $menu = Menu::where('name', $highestQuantityOrder->menu_name)->first();

                    // Add the image URL to the delivery for rendering in Blade
                    $delivery->image_url = $menu ? asset('storage/' . $menu->image) : null;
                }
            }

            return $delivery;
        });

        return view('admin.viewOrders', compact('deliveriesWithImages'));
    }




    public function monitoring()
    {
        return view('admin.monitoring');
    }
}
