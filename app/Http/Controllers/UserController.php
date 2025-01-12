<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Message;
use App\Models\Delivery;
use App\Models\Feedback;

class UserController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch top categories based on total orders
        $topCategoriesWithOrders = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->join('categories', 'menus.category', '=', 'categories.category')
            ->select(
                'categories.category',
                'categories.image',
                DB::raw('SUM(orders.quantity) as menu_count') // Count orders (sum of quantities)
            )
            ->groupBy('categories.category', 'categories.image')
            ->orderBy('menu_count', 'desc')
            ->get();

        // Fetch remaining categories with no orders, limited to complete the top 5 list
        $remainingCategories = DB::table('categories')
            ->leftJoin('menus', 'categories.category', '=', 'menus.category')
            ->leftJoin('orders', 'menus.name', '=', 'orders.menu_name')
            ->select(
                'categories.category',
                'categories.image',
                DB::raw('IFNULL(SUM(orders.quantity), 0) as menu_count') // Handle categories with no orders
            )
            ->groupBy('categories.category', 'categories.image')
            ->havingRaw('menu_count = 0')
            ->take(5 - $topCategoriesWithOrders->count())
            ->get();

        // Merge and limit to 5 top categories
        $topCategories = $topCategoriesWithOrders->merge($remainingCategories)->take(5);

        // Fetch the 4 latest menus and calculate average ratings
        $latestMenus = DB::table('menus')
            ->select('id', 'name', 'image', 'price', 'discount', 'availability', 'created_at')
            ->where('availability', 'Available') // Include only available menus
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($menu) {
                $menu->discounted_price = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating');
                $menu->rating = round($menu->rating, 1);
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count();
                return $menu;
            });

        // Fetch the 4 most popular menus based on order count, including average ratings
        $popularMenus = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.discount', 'menus.availability', DB::raw('COUNT(orders.id) as order_count'))
            ->where('menus.availability', 'Available') // Include only available menus
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.discount', 'menus.availability')
            ->orderByDesc('order_count')
            ->take(4)
            ->get()
            ->map(function ($menu) {
                $menu->discounted_price = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating');
                $menu->rating = round($menu->rating, 1);
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count();
                return $menu;
            });

        // Fetch the 4 highest-rated menus
        $highestRatedMenus = DB::table('menus')
            ->select('id', 'name', 'image', 'price', 'discount', 'availability', 'rating')
            ->where('availability', 'Available') // Include only available menus
            ->whereNotNull('rating')
            ->orderByDesc('rating')
            ->take(4)
            ->get()
            ->map(function ($menu) {
                $menu->discounted_price = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count();
                return $menu;
            });

        // Fetch 5 menus with the highest discount
        $bestDeals = DB::table('menus')
            ->select('id', 'name', 'image', 'price', 'discount', 'availability')
            ->where('availability', 'Available') // Include only available menus
            ->where('discount', '>', 0) // Only menus with a discount
            ->orderByDesc('discount') // Order by discount descending
            ->take(5) // Limit to 5 menus
            ->get()
            ->map(function ($menu) {
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating');
                $menu->rating = round($menu->rating, 1);
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count();
                return $menu;
            });


        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('user.dashboard', compact('userCart', 'pendingOrdersCount', 'user', 'userFavorites', 'topCategories', 'latestMenus', 'popularMenus', 'unreadCount', 'highestRatedMenus', 'bestDeals'));
    }

    // public function userUpdate(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'contact_number' => 'required|string|max:20',
    //         'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
    //         'password' => 'nullable|string|min:8|confirmed', // Only validate if provided
    //     ]);

    //     /** @var User $user */
    //     $user = Auth::user();

    //     $user->update([
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'contact_number' => $request->contact_number,
    //         'email' => $request->email,
    //     ]);

    //     // Update password if provided
    //     if ($request->filled('password')) {
    //         $user->update([
    //             'password' => Hash::make($request->password),
    //         ]);
    //     }

    //     // Set a toast session with the success message
    //     session()->flash('toast', [
    //         'message' => 'Profile updated successfully.',
    //         'type' => 'success', // 'success' or 'error'
    //     ]);

    //     return redirect()->back()->with('success', 'Profile updated successfully!');
    // }

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
            'type' => 'success', // 'success' or 'error'
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function submitExperience(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        session()->flash('toast', [
            'message' => 'Thank you for your feedback!',
            'type' => 'success',
        ]);

        return redirect()->back();
    }


    // Modified menu method in your Controller

    // public function menu(Request $request)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Fetch all categories
    //     $categories = DB::table('categories')
    //         ->leftJoin('menus', 'categories.category', '=', 'menus.category')
    //         ->select('categories.category as category', DB::raw('count(menus.id) as menu_count'))
    //         ->groupBy('categories.category')
    //         ->orderByDesc('menu_count')
    //         ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');
    //     $search = $request->input('search', ''); // Search keyword
    //     $sort = $request->input('sort', 'Cheapest'); // Sort criteria

    //     // Base query for menus
    //     $menusQuery = Menu::query();

    //     // Filter menus by category
    //     if ($selectedCategory !== 'All Menus') {
    //         $menusQuery->where('category', $selectedCategory);
    //     }

    //     // Filter menus by search keyword
    //     if (!empty($search)) {
    //         $menusQuery->where('name', 'LIKE', '%' . $search . '%');
    //     }

    //     // Apply sorting by price or rating
    //     if ($sort === 'Rating') {
    //         $menusQuery->orderByDesc(DB::raw('(SELECT AVG(rating) FROM feedback WHERE feedback.menu_items LIKE CONCAT("%", menus.name, "%"))'));
    //     } else {
    //         $menusQuery->orderBy('price', $sort === 'Expensive' ? 'desc' : 'asc');
    //     }

    //     // Retrieve menus with average ratings and rating counts
    //     $menus = $menusQuery->get()->map(function ($menu) {
    //         $menu->rating = DB::table('feedback')
    //             ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //             ->avg('rating');
    //         $menu->ratingCount = DB::table('feedback')
    //             ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //             ->count();
    //         return $menu;
    //     });

    //     // Count unread messages from the admin
    //     $unreadCount = Message::where('receiver_id', $user->id)
    //         ->where('is_read', false)
    //         ->count();

    //     // Count pending or active orders
    //     $pendingOrdersCount = DB::table('deliveries')
    //         ->where('email', $user->email)
    //         ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
    //         ->count();

    //     // Return the view with required data
    //     return view('user.menu', compact('menus', 'pendingOrdersCount', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites', 'unreadCount'));
    // }

    public function menu(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch all categories
        $categories = DB::table('categories')
            ->leftJoin('menus', 'categories.category', '=', 'menus.category')
            ->select('categories.category as category', DB::raw('count(menus.id) as menu_count'))
            ->groupBy('categories.category')
            ->orderByDesc('menu_count')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');
        $search = $request->input('search', ''); // Search keyword
        $sort = $request->input('sort', 'Cheapest'); // Sort criteria

        // Base query for menus
        $menusQuery = Menu::query();

        // Filter menus by category
        if ($selectedCategory !== 'All Menus') {
            $menusQuery->where('category', $selectedCategory);
        }

        // Filter menus by search keyword
        if (!empty($search)) {
            $menusQuery->where('name', 'LIKE', '%' . $search . '%');
        }

        // Apply sorting by availability, price, or rating
        if ($sort === 'Availability') {
            // Filter menus with 'Available' in availability
            $menusQuery->where('availability', 'Available');
        } elseif ($sort === 'Rating') {
            $menusQuery->orderByDesc(DB::raw('(SELECT AVG(rating) FROM feedback WHERE feedback.menu_items LIKE CONCAT("%", menus.name, "%"))'));
        } else {
            $menusQuery->orderBy('price', $sort === 'Expensive' ? 'desc' : 'asc');
        }

        // Retrieve menus with average ratings and rating counts
        $menus = $menusQuery->get()->map(function ($menu) {
            $menu->rating = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->avg('rating');
            $menu->ratingCount = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->count();
            return $menu;
        });

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Return the view with required data
        return view('user.menu', compact('menus', 'pendingOrdersCount', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites', 'unreadCount'));
    }


    // public function menuView($id)
    // {
    //     $menu = Menu::find($id);

    //     if (!$menu) {
    //         return response()->json(['error' => 'Menu not found'], 404);
    //     }

    //     // Fetch the total number of users who marked this menu as favorite
    //     $favoriteCount = DB::table('favorite_items')->where('menu_id', $id)->count();

    //     // Fetch dynamic rating and review count
    //     $rating = DB::table('feedback')
    //         ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //         ->avg('rating');
    //     $ratingCount = DB::table('feedback')
    //         ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //         ->count();

    //     // Calculate discounted price if applicable
    //     $discountedPrice = $menu->discount > 0
    //         ? round($menu->price * (1 - $menu->discount / 100), 2)
    //         : $menu->price;

    //     return response()->json([
    //         'name' => $menu->name,
    //         'category' => $menu->category,
    //         'price' => $menu->price,
    //         'discountedPrice' => $discountedPrice, // Add discounted price
    //         'discount' => $menu->discount, // Include discount percentage
    //         'description' => $menu->description,
    //         'image' => $menu->image,
    //         'rating' => $rating ?: 0,
    //         'ratingCount' => $ratingCount ?: 0,
    //         'favoriteCount' => $favoriteCount, // Include total favorites count
    //     ]);
    // }

    public function menuView($id)
    {
        $menu = Menu::find($id); // Find menu by its primary key

        if (!$menu) {
            return response()->json(['error' => 'Menu not found'], 404);
        }

        // Calculate discounted price
        $discountedPrice = $menu->discount > 0
            ? round($menu->price * (1 - $menu->discount / 100), 2)
            : $menu->price;

        // Fetch total favorites and ratings
        $favoriteCount = DB::table('favorite_items')->where('menu_id', $id)->count();
        $rating = DB::table('feedback')
            ->where('menu_items', 'LIKE', "%{$menu->name}%")
            ->avg('rating');
        $ratingCount = DB::table('feedback')
            ->where('menu_items', 'LIKE', "%{$menu->name}%")
            ->count();

        // Return JSON response
        return response()->json([
            'name' => $menu->name,
            'category' => $menu->category,
            'price' => $menu->price,
            'discountedPrice' => $discountedPrice,
            'discount' => $menu->discount,
            'description' => $menu->description,
            'image' => $menu->image,
            'rating' => $rating ?: 0,
            'ratingCount' => $ratingCount ?: 0,
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

    // public function shoppingCart()
    // {
    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Get menus added to cart by the current user along with their cart_items IDs and quantities
    //     $menus = DB::table('menus')
    //         ->join('cart_items', 'menus.id', '=', 'cart_items.menu_id')
    //         ->where('cart_items.user_id', $user->id)
    //         ->select('menus.*', 'cart_items.id as cart_item_id', 'cart_items.quantity') // Include quantity here
    //         ->get();

    //     // Count pending or active orders
    //     $pendingOrdersCount = DB::table('deliveries')
    //         ->where('email', $user->email)
    //         ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
    //         ->count();

    //     // Count unread messages from the admin
    //     $unreadCount = Message::where('receiver_id', $user->id)
    //         ->where('is_read', false)
    //         ->count();

    //     return view('user.shoppingCart', compact('user', 'pendingOrdersCount', 'menus', 'userCart', 'userFavorites', 'unreadCount'));
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
            ->select(
                'menus.*',
                'cart_items.id as cart_item_id',
                'cart_items.quantity',
                DB::raw('CASE 
                        WHEN menus.discount > 0 THEN menus.price * (1 - menus.discount / 100) 
                        ELSE menus.price 
                     END AS discounted_price'),
                DB::raw('CASE 
                        WHEN menus.discount > 0 THEN menus.price * (1 - menus.discount / 100) * cart_items.quantity
                        ELSE menus.price * cart_items.quantity 
                     END AS total_price')
            ) // Include calculated prices
            ->get();

        // Calculate the total cart price
        $totalPrice = $menus->sum('total_price');

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('user.shoppingCart', compact('user', 'pendingOrdersCount', 'menus', 'userCart', 'userFavorites', 'unreadCount', 'totalPrice'));
    }


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
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Fetch categories with counts of favorite menus for the logged-in user
    //     $categories = DB::table('categories')
    //         ->leftJoin('menus', 'categories.category', '=', 'menus.category')
    //         ->leftJoin('favorite_items', function ($join) use ($user) {
    //             $join->on('menus.id', '=', 'favorite_items.menu_id')
    //                 ->where('favorite_items.user_id', '=', $user->id);
    //         })
    //         ->select('categories.category as category', DB::raw('count(favorite_items.id) as menu_count'))
    //         ->groupBy('categories.category')
    //         ->orderByDesc('menu_count')
    //         ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     // Retrieve favorite menus filtered by the selected category
    //     if ($selectedCategory == 'All Menus') {
    //         $menus = $user->favoriteItems; // Get all favorite items
    //     } else {
    //         $menus = $user->favoriteItems
    //             ->filter(function ($menu) use ($selectedCategory) {
    //                 return $menu->category === $selectedCategory; // Filter by category
    //             });
    //     }

    //     // Enhance each menu item with its rating and review count
    //     $menus = $menus->map(function ($menu) {
    //         $menu->rating = DB::table('feedback')
    //             ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //             ->avg('rating') ?: 0; // Default to 0 if no rating
    //         $menu->ratingCount = DB::table('feedback')
    //             ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //             ->count(); // Count reviews
    //         return $menu;
    //     });

    //     // Count pending or active orders
    //     $pendingOrdersCount = DB::table('deliveries')
    //         ->where('email', $user->email)
    //         ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
    //         ->count();

    //     // Count unread messages from the admin
    //     $unreadCount = Message::where('receiver_id', $user->id)
    //         ->where('is_read', false)
    //         ->count();

    //     return view('user.favorites', compact('menus', 'pendingOrdersCount', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites', 'unreadCount'));
    // }


    public function favorites(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch all categories with counts of favorite menus
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
        $search = $request->input('search', ''); // Search keyword
        $sort = $request->input('sort', 'Cheapest'); // Sort criteria

        // Base query for the user's favorite menus
        $menusQuery = Menu::join('favorite_items', 'menus.id', '=', 'favorite_items.menu_id')
            ->where('favorite_items.user_id', $user->id)
            ->select('menus.*'); // Ensure we get the original menu ID

        // Filter by selected category
        if ($selectedCategory !== 'All Menus') {
            $menusQuery->where('menus.category', $selectedCategory);
        }

        // Search by menu name
        if (!empty($search)) {
            $menusQuery->where('menus.name', 'LIKE', '%' . $search . '%');
        }

        // Apply sorting by price, availability, or rating
        if ($sort === 'Availability') {
            $menusQuery->where('menus.availability', 'Available');
        } elseif ($sort === 'Rating') {
            $menusQuery->orderByDesc(DB::raw('(SELECT AVG(rating) FROM feedback WHERE feedback.menu_items LIKE CONCAT("%", menus.name, "%"))'));
        } else {
            $menusQuery->orderBy('menus.price', $sort === 'Expensive' ? 'desc' : 'asc');
        }

        // Retrieve favorite menus with additional rating and review count
        $menus = $menusQuery->get()->map(function ($menu) {
            $menu->rating = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->avg('rating');
            $menu->ratingCount = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->count();
            return $menu;
        });

        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('user.favorites', compact('menus', 'categories', 'selectedCategory', 'search', 'sort', 'userCart', 'userFavorites', 'pendingOrdersCount', 'unreadCount', 'user'));
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

        // Fetch cart items with pivot data and calculate discounted prices
        $menus = $user->cartItems()->withPivot('quantity')->get()->map(function ($menu) {
            $menu->discounted_price = $menu->discount > 0
                ? round($menu->price * (1 - $menu->discount / 100), 2)
                : $menu->price;

            return $menu;
        });

        $hasDiscount = $user->has_discount;

        // Pass data to the order view
        return view('user.order', compact('user', 'menus', 'hasDiscount'));
    }

    // public function orderRepeat($deliveryId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Fetch delivery details
    //     $delivery = Delivery::findOrFail($deliveryId);

    //     // Fetch related orders
    //     $orders = $delivery->orders;

    //     // Ensure there are orders associated with the delivery
    //     if ($orders->isEmpty()) {
    //         abort(404, 'No orders found for this delivery.');
    //     }

    //     // Prepare data to pass to the order page
    //     $menus = [];
    //     foreach ($orders as $order) {
    //         $menu = $order->menu; // Use the 'menu' relationship

    //         $menus[] = [
    //             'name' => $order->menu_name,
    //             'quantity' => $order->quantity,
    //             'price' => $menu->price ?? 0, // Fallback to 0 if no menu is found
    //             'image' => $menu->image ?? '', // Fallback to empty string if no menu is found
    //         ];
    //     }

    //     // Redirect to order.blade.php with data
    //     return view('user.order', compact('menus', 'user', 'delivery'));
    // }

    
    public function orderRepeat($deliveryId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch delivery details
        $delivery = Delivery::findOrFail($deliveryId);

        // Fetch related orders
        $orders = $delivery->orders;

        // Ensure there are orders associated with the delivery
        if ($orders->isEmpty()) {
            abort(404, 'No orders found for this delivery.');
        }

        // Prepare data to pass to the order page
        $menus = [];
        foreach ($orders as $order) {
            $menu = $order->menu; // Use the 'menu' relationship

            $menus[] = [
                'name' => $order->menu_name,
                'quantity' => $order->quantity,
                'price' => $menu->price ?? 0, // Fallback to 0 if no menu is found
                'image' => $menu->image ?? '', // Fallback to empty string if no menu is found
                'discounted_price' => $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price, // Calculate discounted price
            ];
        }

        // Redirect to order.blade.php with data
        return view('user.orderRepeat', compact('menus', 'user', 'delivery'));
    }



    // public function orderView($id)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Retrieve the specific menu item by ID
    //     $menu = Menu::find($id);

    //     // Check if the menu item exists
    //     if (!$menu) {
    //         return redirect()->route('user.menu')->with('error', 'Menu item not found');
    //     }

    //     // Fetch the quantity from the request, default to 1
    //     $quantity = request()->input('quantity', 1);

    //     // Calculate the menu's discounted price if it has a discount
    //     $discountedPrice = $menu->discount > 0
    //         ? round($menu->price * (1 - $menu->discount / 100), 2)
    //         : $menu->price;

    //     // Calculate the original total price using the discounted price
    //     $originalTotal = $discountedPrice * $quantity;

    //     // Determine if the user is eligible for a discount
    //     $hasDiscount = $user->has_discount; // Eligible if has_discount is `true`
    //     $finalTotal = $hasDiscount ? $originalTotal * 0.95 : $originalTotal;

    //     // Round the final total
    //     $finalTotal = round($finalTotal);

    //     // Pass the variables to the view
    //     return view('user.orderView', compact('menu', 'user', 'quantity', 'originalTotal', 'finalTotal', 'hasDiscount', 'discountedPrice'));
    // }



    public function orderView($id)
    {
        $user = Auth::user();
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('user.menu')->with('error', 'Menu item not found');
        }

        $quantity = request()->input('quantity', 1);
        $discountedPrice = $menu->discount > 0
            ? round($menu->price * (1 - $menu->discount / 100), 2)
            : $menu->price;

        $originalTotal = $discountedPrice * $quantity;

        $hasDiscount = $user->has_discount;
        $finalTotal = $hasDiscount ? $originalTotal * 0.95 : $originalTotal;
        $finalTotal = round($finalTotal);

        // Remove the default shipping fee; rely entirely on front-end updates
        return view('user.orderView', compact('menu', 'user', 'quantity', 'originalTotal', 'finalTotal', 'hasDiscount', 'discountedPrice'));
    }


    public function menuDetails($id)
    {
        // Fetch the single menu item based on the provided ID
        $menu = Menu::findOrFail($id);

        // Add discounted price to the menu
        $menu->discounted_price = $menu->discount > 0
            ? round($menu->price * (1 - $menu->discount / 100), 2)
            : $menu->price;

        // Count the number of users who added this menu to their favorites
        $favoritesCount = DB::table('favorite_items')->where('menu_id', $menu->id)->count();

        // Calculate the average rating and the rating count for the menu
        $menu->rating = DB::table('feedback')
            ->where('menu_items', 'LIKE', "%{$menu->name}%")
            ->avg('rating');
        $menu->ratingCount = DB::table('feedback')
            ->where('menu_items', 'LIKE', "%{$menu->name}%")
            ->count();

        /** @var User $user */
        $user = Auth::user();
        $userCart = $user ? $user->cart : 0;
        $userFavorites = $user ? $user->favoriteItems()->count() : 0;

        // Count unread messages from the admin
        $unreadCount = $user ? Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count() : 0;

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        return view('user.menuDetails', compact('menu', 'pendingOrdersCount', 'user', 'userCart', 'userFavorites', 'favoritesCount', 'unreadCount'));
    }


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

        // Calculate the menu's discounted price if it has a discount
        $discountedPrice = $menu->discount > 0
            ? round($menu->price * (1 - $menu->discount / 100), 2)
            : $menu->price;

        // Calculate the original total price using the discounted price
        $originalTotal = $discountedPrice * $quantity;

        // Determine if the user is eligible for a discount
        $hasDiscount = $user->has_discount; // Eligible if has_discount is `true`
        $finalTotal = $hasDiscount ? $originalTotal * 0.95 : $originalTotal;

        // Pass the variables to the view
        return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity', 'originalTotal', 'finalTotal', 'hasDiscount', 'discountedPrice'));
    }



    public function orders(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $menus = Menu::all();
        $userFavorites = $user->favoriteItems()->count();

        // Check which menu items have been reviewed by the user
        $reviewedMenus = Feedback::where('customer_name', $user->first_name . ' ' . $user->last_name)
            ->pluck('menu_items')
            ->toArray();

        // Fetch user-specific orders
        $orders = DB::table('deliveries')
            ->where('email', $user->email) // Filter by user's email
            ->orderByRaw("
                FIELD(status, 'Out for Delivery', 'Preparing', 'Pending', 'Delivered', 'Returned'),
                created_at DESC
            ") // Custom ordering
            ->get();

        $orders = $orders->map(function ($order) {
            $order->created_at = Carbon::parse($order->created_at);

            // Parse order items and quantities
            $orderItems = explode(', ', $order->order);
            $quantities = explode(', ', $order->quantity);

            // Fetch all menu details for the order
            $menuDetails = [];
            foreach ($orderItems as $index => $item) {
                $menuName = explode(' (', $item)[0]; // Extract menu name
                $menu = DB::table('menus')->where('name', $menuName)->first();

                if ($menu) {
                    $menuDetails[] = (object)[ // Convert to an object
                        'name' => $menuName,
                        'quantity' => $quantities[$index] ?? 1,
                        'image' => 'storage/' . $menu->image,
                        'price' => $menu->price,
                    ];
                }
            }

            $order->menuDetails = $menuDetails;
            $order->rider_name = $order->rider;
            return $order;
        });

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Categorize orders by status
        $statuses = [
            'all' => $orders,
            'pending-gcash-transaction' => $orders->where('status', 'Pending GCash Transaction'),
            'pending' => $orders->where('status', 'Pending'),
            'preparing' => $orders->where('status', 'Preparing'),
            'out-for-delivery' => $orders->where('status', 'Out for Delivery'),
            'delivered' => $orders->where('status', 'Delivered'),
            'returns' => $orders->where('status', 'Returned'),
        ];

        return view('user.orders', compact('statuses', 'userCart', 'userFavorites', 'unreadCount', 'pendingOrdersCount', 'reviewedMenus'));
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

        // Attach menu images to deliveries
        $deliveries = $deliveries->map(function ($delivery) {
            // Split orders and quantities
            $orderItems = explode(', ', $delivery->order);
            $quantities = explode(', ', $delivery->quantity);

            // Clean menu names to remove quantity suffix (e.g., Pizza (x5))
            $cleanedOrderItems = array_map(function ($item) {
                return preg_replace('/\s*\(x\d+\)$/', '', $item);
            }, $orderItems);

            // Fetch menu images using cleaned names
            $menuData = Menu::whereIn('name', $cleanedOrderItems)
                ->pluck('image', 'name') // Fetch images keyed by menu names
                ->toArray();

            // Determine the menu item with the highest quantity
            $maxQuantityIndex = 0;
            $maxQuantity = 0;

            foreach ($quantities as $index => $quantity) {
                $currentQuantity = (int) trim($quantity);
                if ($currentQuantity > $maxQuantity) {
                    $maxQuantity = $currentQuantity;
                    $maxQuantityIndex = $index;
                }
            }

            // Get the name of the menu with the highest quantity
            $menuWithHighestQuantity = $cleanedOrderItems[$maxQuantityIndex] ?? null;

            // Attach the image for the highest quantity menu
            $delivery->menuImage = $menuData[$menuWithHighestQuantity] ?? asset('images/logo.jpg');

            return $delivery;
        });

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        return view('user.messages', compact('userCart', 'pendingOrdersCount', 'user', 'userFavorites', 'latestMessage', 'unreadCount', 'deliveries'));
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

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        return view('user.messagesPisces', compact('messages', 'pendingOrdersCount', 'userCart', 'user', 'userFavorites'));
    }

    public function sendMessage(Request $request, $userId)
    {
        try {
            // Validate input for either message_text or image
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

                // Set the message text to "Sent an image" if it's an image-only message
                if (!$messageText) {
                    $messageText = 'Sent an image';
                }
            }

            // Create the message
            $message = Message::create([
                'user_id' => Auth::id(),
                'receiver_id' => $userId,
                'sender_role' => 'User',
                'message_text' => $messageText,
                'image_url' => $imageUrl,
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => $message], 201);
        } catch (\Exception $e) {
            logger('Error in sending message:', [$e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send the message.'], 500);
        }
    }

    public function markMessagesAsRead($userId)
    {
        Message::where('user_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->update(['is_read' => true]); // Add is_read column

        return response()->json(['status' => 'success']);
    }


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

        // Map each delivery to include the image of the menu with the highest quantity
        $deliveries = $deliveries->map(function ($delivery) {
            // Split orders and quantities
            $orderItems = explode(', ', $delivery->order);
            $quantities = explode(', ', $delivery->quantity);

            // Clean menu names to remove quantity suffix (e.g., Pizza (x5))
            $cleanedOrderItems = array_map(function ($item) {
                return preg_replace('/\s*\(x\d+\)$/', '', $item);
            }, $orderItems);

            // Fetch menu images using cleaned names
            $menuData = Menu::whereIn('name', $cleanedOrderItems)
                ->pluck('image', 'name') // Fetch images keyed by menu names
                ->toArray();

            // Determine the menu item with the highest quantity
            $maxQuantityIndex = 0;
            $maxQuantity = 0;

            foreach ($quantities as $index => $quantity) {
                $currentQuantity = (int) trim($quantity);
                if ($currentQuantity > $maxQuantity) {
                    $maxQuantity = $currentQuantity;
                    $maxQuantityIndex = $index;
                }
            }

            // Get the name of the menu with the highest quantity
            $menuWithHighestQuantity = $cleanedOrderItems[$maxQuantityIndex] ?? null;

            // Attach the image for the highest quantity menu
            if (isset($menuData[$menuWithHighestQuantity])) {
                $delivery->menuImage = asset('storage/' . $menuData[$menuWithHighestQuantity]);
            } else {
                $delivery->menuImage = asset('images/logo.jpg'); // Default fallback
            }

            return $delivery;
        });

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        return view('user.shopUpdates', compact('userCart', 'pendingOrdersCount', 'user', 'userFavorites', 'latestMessage', 'unreadCount', 'deliveries'));
    }




    public function trackOrder(Request $request, Delivery $delivery)
    {
        /** @var User $user */
        $user = Auth::user();

        // Find all statuses for this delivery
        $statuses = Delivery::where('id', $delivery->id)->get();
        $deliveries = Delivery::findOrFail($delivery->id);

        // Fetch additional data for the view
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Count unread messages from the admin
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Pass timeline to the view
        return view('user.trackOrder', compact('categories', 'unreadCount', 'pendingOrdersCount', 'userCart', 'user', 'userFavorites', 'statuses', 'deliveries'));
    }


    public function reviewOrder(Request $request, $deliveryId)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Fetch the delivery by ID and ensure it belongs to the authenticated user
        $delivery = Delivery::where('id', $deliveryId)
            ->where('email', $user->email) // Match with the user's email to validate
            ->firstOrFail();

        $totalDatabase = $delivery->total_price;

        // Parse the orders and quantities from the database
        $orders = explode(',', $delivery->order); // Split items by commas
        $items = [];
        $subtotal = 0; // Initialize subtotal price

        foreach ($orders as $order) {
            // Extract the menu item name and quantity (e.g., "Burger (x2)")
            preg_match('/^(.*?)\s*\(x(\d+)\)$/', trim($order), $matches);
            $itemName = $matches[1] ?? trim($order); // Extracted name or fallback to original
            $quantity = (int) ($matches[2] ?? 1); // Default to 1 if not found

            // Find the menu item by name
            $menu = Menu::where('name', trim($itemName))->first();

            if ($menu && is_object($menu)) {
                // Calculate the discounted price
                $discountedPrice = $menu->discount > 0
                    ? $menu->price - ($menu->price * $menu->discount / 100)
                    : $menu->price;

                // Calculate item's total price with the discounted price
                $itemTotal = $discountedPrice * $quantity;

                // Add to the overall subtotal
                $subtotal += $itemTotal;

                $items[] = [
                    'name' => $menu->name,
                    'price' => $menu->price,
                    'discounted_price' => $discountedPrice,
                    'total_price' => $itemTotal,
                    'image' => $menu->image,
                    'quantity' => $quantity,
                    'discount' => $menu->discount,
                ];
            } else {
                // Fallback for missing menu items
                Log::warning('Menu item not found: ' . $order);
                $items[] = [
                    'name' => $itemName,
                    'price' => 0, // Default price for missing items
                    'total_price' => 0,
                    'image' => null,
                    'quantity' => $quantity,
                    'discount' => 0,
                ];
            }
        }

        // Calculate the total price (Subtotal + Shipping Fee)
        $shippingFee = $delivery->shipping_fee ?? 0; // Default to 0 if not set
        $totalPrice = $subtotal + $shippingFee;

        // Update the total price in the delivery table if necessary
        // $delivery->total_price = $subtotal; // Store the subtotal
        // $delivery->save();

        // $coupons = $totalPrice - $totalDatabase;
        $coupons = $subtotal * 0.05;

        // Count unread messages
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Count pending or active orders
        $pendingOrdersCount = DB::table('deliveries')
            ->where('email', $user->email)
            ->whereIn('status', ['Pending GCash Transaction', 'Pending', 'Preparing', 'Out for Delivery'])
            ->count();

        // Return the view with parsed data
        return view('user.reviewOrder', compact(
            'delivery',
            'unreadCount',
            'pendingOrdersCount',
            'items',
            'subtotal',
            'shippingFee',
            'totalPrice',
            'userCart',
            'userFavorites',
            'totalDatabase',
            'coupons',
        ));
    }


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
