<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Message;
use App\Models\Feedback;
use App\Models\Home;

class AdminController extends Controller
{
    public function dashboard(UnreadMessagesController $unreadMessagesController)
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

        // Fetch menus with ratings and the number of reviewers
        $highestRatedMenus = Menu::select('menus.id', 'menus.name', 'menus.image', 'menus.category', 'menus.price')
            ->get()
            ->map(function ($menu) {
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating');
                $menu->review_count = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count();
                return $menu;
            })
            ->filter(function ($menu) {
                return $menu->review_count > 0;
            })
            ->sortByDesc('rating')
            ->take(5);

        // Calculate total sales for each month (only 'Delivered' orders)
        $monthlySales = Delivery::select(
            DB::raw("SUM(total_price) as total_sales"),
            DB::raw("MONTHNAME(created_at) as month_name")
        )
            ->where('status', 'Delivered')
            ->groupBy(DB::raw("MONTH(created_at)"), DB::raw("MONTHNAME(created_at)"))
            ->orderBy(DB::raw("MONTH(created_at)"))
            ->pluck('total_sales', 'month_name');

        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        return view('admin.dashboard', compact(
            'userCount',
            'deliveryCount',
            'menuCount',
            'categoryCount',
            'topPicks',
            'highestRatedMenus',
            'monthlySales',
            'totalUnreadCount'
        ));
    }

    public function userUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'newsletter_subscription' => $request->has('newsletter_subscription'), // Updates newsletter_subscription
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Set a toast session with the success message
        session()->flash('toast', [
            'message' => 'Profile updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function saveFeedback(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        try {
            // Save to the homes table
            Home::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'rating' => $validated['rating'],
                'feedback' => $validated['feedback'],
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Feedback saved successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to save feedback. Please try again.',
                'type' => 'error', // Toast type for error
            ]);
        }

        // Redirect back to the previous page
        return redirect()->back();
    }


    public function userDestroy(string $id)
    {
        $user = User::findOrFail($id);

        try {
            // Delete the user
            $user->delete();

            // Set success toast message
            session()->flash('toast', [
                'message' => 'User deleted successfully.',
                'type' => 'success', // Toast type for success
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to delete user. Please try again.',
                'type' => 'error',
            ]);
        }

        // Redirect back to the updates page
        return redirect()->route('admin.updates');
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

    public function feedback(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Fetch search and filter parameters
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'default'); // Default to "default"

        // Query feedbacks with search and filter
        $feedbacks = Feedback::when($search, function ($query, $search) {
            $query->where('customer_name', 'like', '%' . $search . '%')
                ->orWhere('menu_items', 'like', '%' . $search . '%')
                ->orWhere('feedback_text', 'like', '%' . $search . '%');
        })
            ->when($filter === 'name', function ($query) {
                $query->orderBy('customer_name', 'asc'); // Alphabetically by customer name
            })
            ->when($filter === 'menu', function ($query) {
                $query->orderBy('menu_items', 'asc'); // Alphabetically by menu
            })
            ->when($filter === 'rating', function ($query) {
                $query->orderBy('rating', 'desc'); // By rating descending
            })
            ->when($filter === 'withoutResponse', function ($query) {
                $query->whereNull('response'); // Feedbacks without response
            })
            ->when($filter === 'default', function ($query) {
                $query->orderBy('created_at', 'desc'); // Sort by newest feedback first
            })
            ->get(); // Retrieve all results without pagination

        // Pass variables to the view
        return view('admin.feedback', compact('feedbacks', 'filter', 'search', 'totalUnreadCount'));
    }



    public function respondFeedback(Request $request)
    {
        $feedback = Feedback::findOrFail($request->feedback_id);

        // Find the user by matching the customer_name in feedback with first_name and last_name in users
        $user = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), $feedback->customer_name)->first();

        if (!$user) {
            // If no user is found, show an error message
            session()->flash('toast', [
                'message' => 'Error: Unable to send the response message because the user could not be identified.',
                'type' => 'error',
            ]);
            return redirect()->back();
        }

        $feedback->response = $request->response;
        $feedback->save();

        $menuItems = $feedback->menu_items;
        $customerName = $feedback->customer_name;

        $messageText = "Hi, {$customerName}!\n\nThank you for your feedback on {$menuItems}. Here's our response: {$feedback->response}\n\n";

        // Send the message to the identified user
        try {
            $message = Message::create([
                'user_id' => Auth::id(), // The ID of the admin sending the message
                'receiver_id' => $user->id, // The ID of the customer receiving the message
                'sender_role' => 'Admin',
                'message_text' => $messageText,
                'image_url' => null,
                'is_read' => false,
            ]);

            if (!$message) {
                throw new \Exception('Failed to send the message.');
            }
        } catch (\Exception $e) {
            logger('Error in sending message:', [$e->getMessage()]);
            session()->flash('toast', [
                'message' => 'Response submitted, but failed to send the message to the user.',
                'type' => 'error',
            ]);
            return redirect()->back();
        }

        session()->flash('toast', [
            'message' => 'Response has been submitted successfully, and the user has been notified.',
            'type' => 'success',
        ]);

        return redirect()->back();
    }

    public function customerMessages(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }

        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Fetch search and filter parameters
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'recent'); // Default to "recent"

        // Fetch users with the role "User" and apply search and filter
        $users = User::where('role', 'User')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            })
            ->get();

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
        });

        // Apply filtering based on the selected filter
        if ($filter === 'recent') {
            $userMessages = $userMessages->sortByDesc(function ($data) {
                return optional($data['latestMessage'])->created_at;
            });
        } elseif ($filter === 'oldest') {
            $userMessages = $userMessages->sortBy(function ($data) {
                return optional($data['latestMessage'])->created_at;
            });
        } elseif ($filter === 'alphabetical') {
            $userMessages = $userMessages->sortBy(function ($data) {
                return strtolower($data['user']->first_name . ' ' . $data['user']->last_name);
            });
        }

        return view('admin.customerMessages', compact('userMessages', 'filter', 'search', 'totalUnreadCount'));
    }

    public function markMessagesAsRead($userId)
    {
        Message::where('user_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->update(['is_read' => true]); // Add is_read column

        return response()->json(['status' => 'success']);
    }

    public function messageUser($userId)
    {
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($userId);

        // Fetch messages between the authenticated user and the specified user
        $messages = Message::where(function ($query) use ($userId, $authenticatedUser) {
            $query->where('user_id', $authenticatedUser->id)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId, $authenticatedUser) {
            $query->where('user_id', $userId)
                ->where('receiver_id', $authenticatedUser->id);
        })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all unread messages sent by the specified user as read
        Message::where('user_id', $userId)
            ->where('receiver_id', $authenticatedUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.messageUser', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, $userId)
    {
        try {
            // Debug incoming request data
            logger('Request Data:', $request->all());

            // Validate input
            $request->validate([
                'message_text' => 'nullable|required_without:image',
                'image' => 'nullable|required_without:message_text|image|max:2048',
            ]);

            $imageFile = $request->file('image');
            $imageUrl = null;
            $messageText = $request->input('message_text');

            // Handle image upload if present
            if ($imageFile) {
                $imagePath = $imageFile->store('messages', 'public');
                $imageUrl = asset('storage/' . $imagePath);

                logger('Image URL:', [$imageUrl]); // Debug image path

                // Set the message text to "Sent an image" if it's an image-only message
                if (!$messageText) {
                    $messageText = 'Sent an image';
                }
            }

            // Save message to database
            $message = Message::create([
                'user_id' => Auth::id(),
                'receiver_id' => $userId,
                'sender_role' => 'Admin',
                'message_text' => $messageText,
                'image_url' => $imageUrl,
                'is_read' => false,
            ]);

            if (!$message) {
                throw new \Exception('Failed to save the message.');
            }

            return response()->json(['success' => true, 'message' => $message], 201);
        } catch (\Exception $e) {
            logger('Error in sendMessage:', [$e->getMessage()]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }



    // public function updates(Request $request, UnreadMessagesController $unreadMessagesController)
    // {
    //     // Fetch unread message data
    //     $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
    //     $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

    //     // Fetch search and filter parameters
    //     $search = $request->input('search', '');
    //     $filter = $request->input('filter', 'default'); // Default filter

    //     // Query users with search and filter
    //     $users = User::where('role', 'User')
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($subQuery) use ($search) {
    //                 $subQuery->where('first_name', 'like', '%' . $search . '%')
    //                     ->orWhere('last_name', 'like', '%' . $search . '%');
    //             });
    //         })
    //         ->when($filter === 'alphabetical', function ($query) {
    //             $query->orderBy('first_name')->orderBy('last_name'); // Alphabetical order
    //         })
    //         ->when($filter === 'new', function ($query) {
    //             $query->orderBy('created_at', 'desc'); // New customers first
    //         })
    //         ->when($filter === 'old', function ($query) {
    //             $query->orderBy('created_at', 'asc'); // Old customers first
    //         })
    //         ->get(); // Retrieve all results without pagination

    //     // Pass variables to the view
    //     return view('admin.updates', compact('users', 'search', 'filter', 'totalUnreadCount'));
    // }

    public function updates(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Fetch search and filter parameters
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'default'); // Default filter

        // Query users with search and filter
        $users = User::where('role', 'User')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter === 'alphabetical', function ($query) {
                $query->orderBy('first_name')->orderBy('last_name'); // Alphabetical order
            })
            ->when($filter === 'new', function ($query) {
                $query->orderBy('created_at', 'desc'); // New customers first
            })
            ->when($filter === 'old', function ($query) {
                $query->orderBy('created_at', 'asc'); // Old customers first
            })
            ->get(); // Retrieve all results without pagination

        // Pass variables to the view
        return view('admin.updates', compact('users', 'search', 'filter', 'totalUnreadCount'));
    }


    public function viewOrders($userId)
    {
        $user = User::findOrFail($userId);

        $deliveries = Delivery::where('email', $user->email)
            ->with('orders')
            ->orderBy('created_at', 'desc') // Default sorting
            ->get();

        $deliveriesWithImages = $deliveries->map(function ($delivery) {
            $orders = $delivery->orders;

            if ($orders->isNotEmpty()) {
                $highestQuantityOrder = $orders->sortByDesc('quantity')->first();
                if ($highestQuantityOrder) {
                    $menu = Menu::where('name', $highestQuantityOrder->menu_name)->first();
                    $delivery->image_url = $menu ? asset('storage/' . $menu->image) : null;
                }
            }

            return $delivery;
        });

        return view('admin.viewOrders', compact('deliveriesWithImages'));
    }

    public function getOrderDetails($id)
    {
        // Find the delivery record by ID or fail with a 404
        $delivery = Delivery::findOrFail($id);

        // Log the delivery data
        Log::info('Delivery data fetched:', ['delivery' => $delivery]);

        // Split orders to extract menu names
        $orders = explode(', ', $delivery->order);

        // Remove the quantity part (e.g., "(x5)") from each menu name
        $plainMenuNames = array_map(function ($order) {
            return preg_replace('/\s*\(x\d+\)$/', '', $order);
        }, $orders);

        // Log cleaned menu names
        Log::info('Cleaned menu names:', ['menu_names' => $plainMenuNames]);

        // Fetch menu images using the cleaned menu names
        $menuImages = Menu::whereIn('name', $plainMenuNames)
            ->pluck('image', 'name')
            ->map(function ($image) {
                // Use fallback image if the menu item doesn't have an image
                return $image ? asset('storage/' . $image) : asset('images/logo.jpg');
            })
            ->toArray();

        // Log menu images
        Log::info('Menu images fetched:', ['menu_images' => $menuImages]);

        return response()->json([
            'success' => true,
            'delivery' => [
                'name' => $delivery->name,
                'email' => $delivery->email,
                'contact_number' => $delivery->contact_number,
                'address' => $delivery->address,
                'total_price' => $delivery->total_price,
                'status' => $delivery->status,
                'order' => $delivery->order,
                'quantity' => $delivery->quantity,
            ],
            'menu_images' => $menuImages, // Include menu images in the response
        ]);
    }


    public function monitoring()
    {
        return view('admin.monitoring');
    }
}
