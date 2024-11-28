<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Message;
use App\Models\Delivery;

class UserController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch categories with menu counts and prioritize those with at least one menu
        $topCategoriesWithMenus = DB::table('menus')
            ->join('categories', 'menus.category', '=', 'categories.category')
            ->select('categories.category', 'categories.image', DB::raw('COUNT(menus.id) as menu_count'))
            ->groupBy('categories.category', 'categories.image')
            ->orderBy('menu_count', 'desc')
            ->get();

        // Fetch remaining categories that have no menus, limited to complete the top 5 list
        $remainingCategories = DB::table('categories')
            ->leftJoin('menus', 'categories.category', '=', 'menus.category')
            ->select('categories.category', 'categories.image', DB::raw('COUNT(menus.id) as menu_count'))
            ->groupBy('categories.category', 'categories.image')
            ->havingRaw('menu_count = 0')
            ->take(5 - $topCategoriesWithMenus->count())
            ->get();

        // Merge both results, ensuring there are exactly 5 items
        $topCategories = $topCategoriesWithMenus->merge($remainingCategories)->take(5);

        // Fetch the 4 latest menus
        $latestMenus = DB::table('menus')
            ->select('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.created_at')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Fetch the 4 most popular menus based on order count, matching menu names
        $popularMenus = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select('menus.id', 'menus.name', 'menus.image', 'menus.price', DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price')
            ->orderByDesc('order_count')
            ->take(4)
            ->get();


        return view('user.dashboard', compact('userCart', 'user', 'userFavorites', 'topCategories', 'latestMenus', 'popularMenus'));
    }

    // public function menu(Request $request)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Fetch categories with menu counts and sort by menu_count in descending order
    //     $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
    //         ->groupBy('category')
    //         ->orderByDesc('menu_count')
    //         ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');

    //     // Retrieve menus based on selected category, excluding items in the cart
    //     if ($selectedCategory == 'All Menus') {
    //         $menus = Menu::whereNotIn('id', $user->cartItems->pluck('id'))->get();
    //     } else {
    //         $menus = Menu::where('category', $selectedCategory)
    //             ->whereNotIn('id', $user->cartItems->pluck('id'))
    //             ->get();
    //     }

    //     // Pass the sorted categories list to the view
    //     return view('user.menu', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    // }

    public function menu(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch all categories, including those with zero menus
        $categories = DB::table('categories')
            ->leftJoin('menus', 'categories.category', '=', 'menus.category')
            ->select('categories.category as category', DB::raw('count(menus.id) as menu_count'))
            ->groupBy('categories.category')
            ->orderByDesc('menu_count')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        // Count the number of users who added this menu to their favorites
        // $favoritesCount = DB::table('favorite_items')->where('menu_id', $menu->id)->count();

        // Retrieve menus based on selected category
        if ($selectedCategory == 'All Menus') {
            $menus = Menu::all();
        } else {
            $menus = Menu::where('category', $selectedCategory)->get();
        }

        return view('user.menu', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }



    public function menuView($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Menu not found'], 404);
        }

        // Get the total favorite count for this menu
        $favoriteCount = DB::table('favorite_items')->where('menu_id', $id)->count();

        // Mock rating data (adjust as needed)
        $rating = 4.2;
        $ratingCount = 4000;

        return response()->json([
            'name' => $menu->name,
            'category' => $menu->category,
            'price' => $menu->price,
            'description' => $menu->description,
            'image' => $menu->image,
            'rating' => $rating,
            'ratingCount' => $ratingCount,
            'favoriteCount' => $favoriteCount,
        ]);
    }


    // Add To Cart Bawal Duplicate
    public function addToCart(Request $request, $menuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if the menu item is already in the user's cart
        if ($user->cartItems()->where('menu_id', $menuId)->exists()) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Menu is already in the cart!'
            ]);
        }

        // Attach the menu item to the user's cart
        $user->cartItems()->attach($menuId);

        // Increment the cart count
        $user->increment('cart');

        // return redirect()->route('user.menu')->with('toast', [
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Menu added to cart!'
        ]);
    }



    // Add To Cart Pwede Duplicate
    // public function addToCart(Request $request, $menuId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Attach the menu item to the user's cart without checking for duplicates
    //     $user->cartItems()->attach($menuId);

    //     // Increment the cart count
    //     $user->increment('cart');

    //     return redirect()->route('user.menu')->with('success', 'Item added to cart!');
    // }


    // public function addToCartModal(Request $request, $menuId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Check if the menu item is already in the user's cart
    //     if (!$user->cartItems()->where('menu_id', $menuId)->exists()) {
    //         // Attach the menu item to the user's cart
    //         $user->cartItems()->attach($menuId);
    //     }

    //     // Increment the cart count
    //     $user->increment('cart');

    //     // Return a JSON response instead of redirecting
    //     return response()->json(['success' => true, 'message' => 'Item added to cart!']);
    // }


    public function shoppingCart()
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Get menus added to cart by the current user along with their cart_items IDs and quantities
        $menus = DB::table('menus')
            ->join('cart_items', 'menus.id', '=', 'cart_items.menu_id')
            ->where('cart_items.user_id', $user->id)
            ->select('menus.*', 'cart_items.id as cart_item_id', 'cart_items.quantity') // Include quantity here
            ->get();

        return view('user.shoppingCart', compact('user', 'menus', 'userCart', 'userFavorites'));
    }


    // public function addToFavorites(Request $request, $menuId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Check if the item is already in the user's favorites
    //     if ($user->favoriteItems()->where('menu_id', $menuId)->exists()) {
    //         // Remove from favorites if already present
    //         $user->favoriteItems()->detach($menuId);
    //         $user->decrement('favorites');
    //     } else {
    //         // Add to favorites if not present
    //         $user->favoriteItems()->attach($menuId);
    //         $user->increment('favorites');
    //     }

    //     return redirect()->back()->with('success', 'Item added to favorites!');
    // }

    public function addToFavorites(Request $request, $menuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if the item is already in the user's favorites
        if ($user->favoriteItems()->where('menu_id', $menuId)->exists()) {
            // Remove from favorites if already present
            $user->favoriteItems()->detach($menuId);
            $user->decrement('favorites');

            // Return toast for removal
            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'Menu removed from favorites!'
            ]);
        }

        // Add to favorites if not present
        $user->favoriteItems()->attach($menuId);
        $user->increment('favorites');

        // Return toast for addition
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Menu added to favorites!'
        ]);
    }


    // public function favorites(Request $request)
    // {
    //      // Fetch all categories, including those with zero menus
    //      $categories = DB::table('categories')
    //      ->leftJoin('menus', 'categories.category', '=', 'menus.category')
    //      ->select('categories.category as category', DB::raw('count(menus.id) as menu_count'))
    //      ->groupBy('categories.category')
    //      ->orderByDesc('menu_count')
    //      ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');

    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Retrieve favorite menus without excluding items in the cart
    //     if ($selectedCategory == 'All Menus') {
    //         $menus = $user->favoriteItems()->get();
    //     } else {
    //         $menus = $user->favoriteItems()
    //             ->where('menus.category', $selectedCategory)
    //             ->get();
    //     }

    //     return view('user.favorites', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    // }

    public function favorites(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch categories with counts of favorite menus for the logged-in user
        $categories = DB::table('categories')
            ->leftJoin('menus', 'categories.category', '=', 'menus.category')
            ->leftJoin('favorite_items', function ($join) use ($user) {
                $join->on('menus.id', '=', 'favorite_items.menu_id')
                    ->where('favorite_items.user_id', '=', $user->id);
            })
            ->select('categories.category as category', DB::raw('count(favorite_items.id) as menu_count'))
            ->groupBy('categories.category')
            ->orderByDesc('menu_count')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Retrieve favorite menus filtered by the selected category
        if ($selectedCategory == 'All Menus') {
            $menus = $user->favoriteItems; // Get all favorite items
        } else {
            $menus = $user->favoriteItems
                ->filter(function ($menu) use ($selectedCategory) {
                    return $menu->category === $selectedCategory; // Filter by category
                });
        }

        return view('user.favorites', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }




    public function updateQuantity(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();

        // Find the cart item and update quantity directly
        $cartItem = DB::table('cart_items')
            ->where('user_id', $userId)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($cartItem) {
            DB::table('cart_items')
                ->where('user_id', $userId)
                ->where('menu_id', $request->menu_id)
                ->update(['quantity' => $request->quantity]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Cart item not found']);
        }
    }



    public function removeCart($cartItemId)
    {
        /** @var User $user */
        $user = Auth::user();

        // $cart_id = Menu::all();

        // Delete the specific cart item by its ID
        DB::table('cart_items')->where('id', $cartItemId)->delete();

        // Decrement the user's cart count
        $user->decrement('cart');

        return redirect()->route('user.shoppingCart')->with('toast', [
            'type' => 'success',
            'message' => 'Menu successfully removed from the cart!'
        ]);
    }


    public function order()
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch the user's cart items with pivot data (quantity) and menu details (price, name, image)
        $menus = $user->cartItems()->withPivot('quantity')->get();

        // Calculate total price
        // $totalPrice = $menu->price * $quantity;

        return view('user.order', compact('user', 'menus'));
    }
    public function orderView($id)
    {
        // Get the current authenticated user
        $user = Auth::user();

        // Retrieve the specific menu item by ID
        $menu = Menu::find($id);

        // Check if the menu item exists
        if (!$menu) {
            return redirect()->route('user.menu')->with('error', 'Menu item not found');
        }

        // Fetch the quantity from the request, default to 1
        $quantity = request()->input('quantity', 1);

        // Calculate total price
        $totalPrice = $menu->price * $quantity;

        // Pass the menu item and user to the view
        return view('user.orderView', compact('menu', 'totalPrice', 'user'));
    }
    public function menuDetails($id)
    {
        // Fetch the single menu item based on the provided ID
        $menu = Menu::findOrFail($id);

        // Count the number of users who added this menu to their favorites
        $favoritesCount = DB::table('favorite_items')->where('menu_id', $menu->id)->count();

        /** @var User $user */
        $user = Auth::user();
        $userCart = $user ? $user->cart : 0;
        $userFavorites = $user ? $user->favoriteItems()->count() : 0;

        return view('user.menuDetails', compact('menu', 'user', 'userCart', 'userFavorites', 'favoritesCount'));
    }



    // public function menuDetailsOrder($id)
    // {
    //     // Get the current authenticated user
    //     $user = Auth::user();

    //     // Retrieve the specific menu item by ID
    //     $menu = Menu::find($id);

    //     // Check if the menu item exists
    //     if (!$menu) {
    //         return redirect()->route('user.menu')->with('error', 'Menu item not found');
    //     }

    //     // Fetch the quantity from the request, default to 1 if not provided
    //     $quantity = request()->input('quantity', 1);

    //     // Pass the menu item, user, and quantity to the view
    //     return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity'));
    // }

    public function menuDetailsOrder($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the specific menu item by ID
        $menu = Menu::find($id);

        // Check if the menu item exists
        if (!$menu) {
            return redirect()->route('user.menu')->with('error', 'Menu item not found');
        }

        // Fetch the quantity from the request, default to 1
        $quantity = request()->input('quantity', 1);

        // Calculate total price
        $totalPrice = $menu->price * $quantity;

        // Pass the menu item, user, quantity, and total price to the view
        return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity', 'totalPrice'));
    }


    public function orders(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch user-specific orders
        $orders = DB::table('deliveries')
            ->where('email', $user->email) // Filter by user's email
            ->orderBy('created_at', 'desc')
            ->get();

        // Process each order to determine the appropriate image
        $orders = $orders->map(function ($order) {
            $order->created_at = Carbon::parse($order->created_at);

            // Parse the order string and quantities
            $orderItems = explode(', ', $order->order);
            $quantities = explode(', ', $order->quantity);

            // Determine the menu item with the highest quantity or the first item
            $mostOrderedIndex = 0;
            $maxQuantity = 0;
            foreach ($quantities as $index => $quantity) {
                if ((int)$quantity > $maxQuantity) {
                    $mostOrderedIndex = $index;
                    $maxQuantity = (int)$quantity;
                }
            }

            // Extract menu name for the image
            $menuName = explode(' (', $orderItems[$mostOrderedIndex])[0];
            $menu = DB::table('menus')->where('name', $menuName)->first();

            // Assign the image URL or default to logo
            $order->image = $menu ? asset('storage/' . $menu->image) : asset('images/logo.jpg');

            return $order;
        });

        // Categorize orders by status
        $statuses = [
            'all' => $orders,
            'pending' => $orders->where('status', 'Pending'),
            'preparing' => $orders->where('status', 'Preparing'),
            'out_for_delivery' => $orders->where('status', 'Out for Delivery'),
            'delivered' => $orders->where('status', 'Delivered'),
            'returns' => $orders->where('status', 'Returned'),
        ];

        return view('user.orders', compact('statuses', 'userCart', 'userFavorites'));
    }


    public function messages(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch the latest message exchanged between the user and the admin
        $latestMessage = Message::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest('created_at') // Order by most recent message
            ->first(); // Get the latest message

        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Fetch deliveries for the current user
        $deliveries = Delivery::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.messages', compact('userCart', 'user', 'userFavorites', 'latestMessage', 'unreadCount', 'deliveries'));
    }


    public function messagesPisces(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch all messages exchanged with the admin
        $messages = Message::where(function ($query) use ($user) {
            $query->where('user_id', $user->id) // Sent by the user
                ->orWhere('receiver_id', $user->id); // Received by the user
        })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        return view('user.messagesPisces', compact('messages', 'userCart', 'user', 'userFavorites'));
    }

    public function sendMessage(Request $request, $userId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string',
        ]);

        // Create the message
        $message = Message::create([
            'user_id' => Auth::id(), // Sender is the authenticated user
            'receiver_id' => $userId, // Receiver is the admin or target user
            'sender_role' => 'User',
            'message_text' => $validated['message_text'],
        ]);

        // Return only the new message
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }




    public function markMessagesAsRead($userId)
    {
        Message::where('user_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->update(['is_read' => true]); // Add is_read column

        return response()->json(['status' => 'success']);
    }




    // public function shopUpdates(Request $request)
    // {
    //     $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
    //         ->groupBy('category')
    //         ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');

    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Retrieve menus based on selected category, excluding items in the cart
    //     if ($selectedCategory == 'All Menus') {
    //         $menus = Menu::whereNotIn('id', $user->cartItems->pluck('id'))->get();
    //     } else {
    //         $menus = Menu::where('category', $selectedCategory)
    //             ->whereNotIn('id', $user->cartItems->pluck('id'))
    //             ->get();
    //     }

    //     return view('user.shopUpdates', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    // }



    public function shopUpdates(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch the latest message exchanged between the user and the admin
        $latestMessage = Message::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest('created_at') // Order by most recent message
            ->first(); // Get the latest message

        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Fetch deliveries for the current user
        $deliveries = Delivery::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.shopUpdates', compact('userCart', 'user', 'userFavorites', 'latestMessage', 'unreadCount', 'deliveries'));
    }

    public function trackOrder(Request $request)
    {
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Retrieve menus based on selected category, excluding items in the cart
        if ($selectedCategory == 'All Menus') {
            $menus = Menu::whereNotIn('id', $user->cartItems->pluck('id'))->get();
        } else {
            $menus = Menu::where('category', $selectedCategory)
                ->whereNotIn('id', $user->cartItems->pluck('id'))
                ->get();
        }

        return view('user.trackOrder', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }

    public function reviewOrder(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        return view('user.reviewOrder', compact('userCart', 'user', 'userFavorites'));
    }

    // public function reviewOrder(Request $request)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Fetch user's completed orders
    //     $completedOrders = Delivery::where('user_id', $user->id)
    //         ->where('status', 'completed') // Assuming 'completed' indicates delivered orders
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     return view('user.reviewOrder', compact('userCart', 'user', 'userFavorites', 'completedOrders'));
    // }





    // public function menuDetail($menuId)
    // {
    //     $menu = Menu::find($menuId);
    //     // Return the view for the menu details page
    //     return view('user.menuDetail', compact('menu'));
    // }



    public function category($category)
    {
        // Retrieve all menus in the selected category
        $menus = Menu::where('category', $category)->get();

        // Retrieve distinct categories and their menu counts
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        return view('user.menu', compact('menus', 'categories'));
    }
}
