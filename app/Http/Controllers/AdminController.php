<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Message;
use App\Models\Feedback;

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

    public function feedback(Request $request)
    {
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
            ->paginate(4) // Adjust pagination as needed
            ->appends(['search' => $search, 'filter' => $filter]); // Preserve query parameters in pagination links

        return view('admin.feedback', compact('feedbacks', 'filter', 'search'));
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


    public function customerMessages(Request $request)
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            abort(403, 'Unauthorized access.');
        }

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

        return view('admin.customerMessages', compact('userMessages', 'filter', 'search'));
    }




    // public function messageUser($userId)
    // {
    //     /** @var User $authenticatedUser */
    //     $authenticatedUser = Auth::user();

    //     if (!$authenticatedUser) {
    //         abort(403, 'Unauthorized access.');
    //     }

    //     $user = User::findOrFail($userId);

    //     // Fetch messages between the authenticated user and the specified user
    //     $messages = Message::where(function ($query) use ($userId, $authenticatedUser) {
    //         $query->where(function ($q) use ($userId, $authenticatedUser) {
    //             $q->where('user_id', $authenticatedUser->id)
    //                 ->where('receiver_id', $userId);
    //         })->orWhere(function ($q) use ($userId, $authenticatedUser) {
    //             $q->where('user_id', $userId)
    //                 ->where('receiver_id', $authenticatedUser->id);
    //         });
    //     })
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     // Mark all unread messages sent by the specified user as read
    //     Message::where('user_id', $userId)
    //         ->where('receiver_id', $authenticatedUser->id)
    //         ->where('is_read', false)
    //         ->update(['is_read' => true]);

    //     return view('admin.messageUser', compact('user', 'messages'));
    // }


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

    //     $authUser = Auth::user();
    //     if (!$authUser) {
    //         return response()->json(['error' => 'Admin not authenticated'], 403);
    //     }

    //     // Create the message
    //     $message = Message::create([
    //         'user_id' => $authUser->id, // Admin is the sender
    //         'receiver_id' => $userId, // User is the recipient
    //         'sender_role' => 'Admin',
    //         'message_text' => $validated['message_text'],
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => [
    //             'message_text' => $message->message_text,
    //             'created_at' => $message->created_at->diffForHumans(),
    //         ],
    //     ]);
    // }

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

            // Handle image upload
            if ($imageFile) {
                $imagePath = $imageFile->store('messages', 'public');
                $imageUrl = asset('storage/' . $imagePath);

                logger('Image URL:', [$imageUrl]); // Debug image path
            }

            // Save message to database
            $message = Message::create([
                'user_id' => Auth::id(),
                'receiver_id' => $userId,
                'sender_role' => 'Admin',
                'message_text' => $request->input('message_text'),
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


    // public function sendMessage(Request $request, $userId)
    // {
    //     try {
    //         // Validate input for message_text or image
    //         $request->validate([
    //             'message_text' => 'nullable|required_without:image',
    //             'image' => 'nullable|required_without:message_text|image|max:2048',
    //         ]);

    //         $imageFile = $request->file('image');
    //         $imageUrl = null;

    //         // Handle image upload if present
    //         if ($imageFile) {
    //             $imagePath = $imageFile->store('messages', 'public'); // Store in the 'public/messages' directory
    //             $imageUrl = asset('storage/' . $imagePath);
    //         }

    //         // Create the message
    //         $message = Message::create([
    //             'user_id' => Auth::id(),
    //             'receiver_id' => (int) $userId, // Ensure it's an integer
    //             'sender_role' => 'Admin',
    //             'message_text' => $request->input('message_text'), // Can be null
    //             'image_url' => $imageUrl,
    //             'is_read' => false,
    //         ]);

    //         return response()->json(['success' => true, 'message' => $message], 201);
    //     } catch (\Exception $e) {
    //         Log::error('Error sending message:', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Failed to send the message.'], 500);
    //     }
    // }


    // public function updates(Request $request)
    // {
    //     // Fetch only users with the 'User' role
    //     $query = User::where('role', 'User');

    //     // Apply filters based on the selected option
    //     if ($request->has('filter')) {
    //         switch ($request->filter) {
    //             case 'alphabetical':
    //                 $query->orderBy('first_name')->orderBy('last_name');
    //                 break;
    //             case 'new_customers':
    //                 $query->orderBy('created_at', 'desc');
    //                 break;
    //             case 'old_customers':
    //                 $query->orderBy('created_at', 'asc');
    //                 break;
    //         }
    //     }

    //     $users = $query->get();
    //     return view('admin.updates', compact('users'));
    // }

    public function updates(Request $request)
    {
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
            ->paginate(3) // Paginate results
            ->appends(['search' => $search, 'filter' => $filter]); // Preserve query parameters in pagination

        return view('admin.updates', compact('users', 'search', 'filter'));
    }


    // public function viewOrders($userId)
    // {
    //     // Fetch user by ID
    //     $user = User::findOrFail($userId);

    //     // Fetch deliveries linked to the user via email
    //     $deliveries = Delivery::where('email', $user->email)->with('orders')->get();

    //     // Process each delivery to find the menu image with the highest quantity
    //     $deliveriesWithImages = $deliveries->map(function ($delivery) {
    //         $orders = $delivery->orders;

    //         if ($orders->isNotEmpty()) {
    //             // Get the order with the highest quantity
    //             $highestQuantityOrder = $orders->sortByDesc('quantity')->first();

    //             if ($highestQuantityOrder) {
    //                 // Get the menu item associated with the highest quantity order
    //                 $menu = Menu::where('name', $highestQuantityOrder->menu_name)->first();

    //                 // Add the image URL to the delivery for rendering in Blade
    //                 $delivery->image_url = $menu ? asset('storage/' . $menu->image) : null;
    //             }
    //         }

    //         return $delivery;
    //     });

    //     return view('admin.viewOrders', compact('deliveriesWithImages'));
    // }

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


    // public function getOrderDetails($id)
    // {
    //     $delivery = Delivery::find($id);

    //     if (!$delivery) {
    //         return response()->json(['success' => false, 'message' => 'Delivery not found']);
    //     }

    //     $orders = explode(', ', $delivery->order);
    //     $quantities = explode(', ', $delivery->quantity);
    //     $menuImages = Menu::whereIn('name', $orders)
    //         ->pluck('image', 'name')
    //         ->map(fn($image) => $image ? asset('storage/' . $image) : asset('images/logo.jpg'))
    //         ->toArray();

    //     return response()->json([
    //         'success' => true,
    //         'delivery' => $delivery,
    //         'menu_images' => $menuImages,
    //         'quantities' => $quantities
    //     ]);
    // }

    public function getOrderDetails($id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery not found'
            ]);
        }

        // Get orders and quantities from delivery
        $orders = explode(', ', $delivery->order);
        $quantities = explode(', ', $delivery->quantity);

        // Normalize menu names
        $plainMenuNames = array_map(function ($order) {
            return trim(strtolower(preg_replace('/\s*\(x\d+\)$/', '', $order)));
        }, $orders);

        // Retrieve menu images
        $menuImages = Menu::whereIn('name', $plainMenuNames)
            ->pluck('image', 'name')
            ->mapWithKeys(fn($image, $name) => [
                strtolower(trim($name)) => $image ? asset('storage/' . $image) : asset('images/logo.jpg')
            ])
            ->toArray();

        // Log for debugging
        Log::info('Normalized Menu Names: ', $plainMenuNames);
        Log::info('Retrieved Menu Images: ', $menuImages);

        // Match normalized names to images
        $imageUrls = [];
        foreach ($plainMenuNames as $name) {
            $imageUrls[] = $menuImages[$name] ?? asset('images/logo.jpg');
        }

        return response()->json([
            'success' => true,
            'delivery' => $delivery,
            'menu_images' => $imageUrls,
            'quantities' => $quantities
        ]);
    }





    public function monitoring()
    {
        return view('admin.monitoring');
    }
}
