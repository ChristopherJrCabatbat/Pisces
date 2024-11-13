<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;

class UserController extends Controller
{
    // public function dashboard()
    // {
    //     // Retrieve the current logged-in user's cart and favorites values
    //     /** @var User $user */
    //     $user = Auth::user();
    //     $userCart = $user->cart;
    //     $userFavorites = $user->favoriteItems()->count();

    //     return view('user.dashboard', compact('userCart', 'userFavorites'));
    // }

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
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Fetch the 4 most popular menus based on order count, matching menu names
        $popularMenus = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select('menus.name', 'menus.image', 'menus.price', DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price')
            ->orderByDesc('order_count')
            ->take(4)
            ->get();

        return view('user.dashboard', compact('userCart', 'userFavorites', 'topCategories', 'latestMenus', 'popularMenus'));
    }


    public function menu(Request $request)
    {
        // Fetch categories with menu counts and sort by menu_count in descending order
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->orderByDesc('menu_count')
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

        // Pass the sorted categories list to the view
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

    // public function addToCart(Request $request, $menuId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();
    //     $quantity = $request->input('quantity', 1);

    //     // Check if the menu item is already in the user's cart
    //     if ($user->cartItems()->where('menu_id', $menuId)->exists()) {
    //         // Update the quantity if already present
    //         $user->cartItems()->updateExistingPivot($menuId, ['quantity' => DB::raw("quantity + $quantity")]);
    //     } else {
    //         // Attach the menu item to the user's cart with the specified quantity
    //         $user->cartItems()->attach($menuId, ['quantity' => $quantity]);
    //     }

    //     // Increment the cart count
    //     $user->increment('cart');

    //     return response()->json(['success' => true]);
    // }


    public function addToCart(Request $request, $menuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if the menu item is already in the user's cart
        if (!$user->cartItems()->where('menu_id', $menuId)->exists()) {
            // Attach the menu item to the user's cart
            $user->cartItems()->attach($menuId);
        }

        // Increment the cart count
        $user->increment('cart');

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function addToCartModal(Request $request, $menuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if the menu item is already in the user's cart
        $cartItem = $user->cartItems()->where('menu_id', $menuId)->first();

        if ($cartItem) {
            // Increment the quantity if the item already exists
            $cartItem->pivot->quantity += 1;
            $cartItem->pivot->save();
        } else {
            // Attach the menu item to the user's cart with an initial quantity of 1
            $user->cartItems()->attach($menuId, ['quantity' => 1]);
        }

        // Increment the cart count in the user's profile
        $user->increment('cart');

        // Check if the request is via AJAX (JavaScript fetch or Axios)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart!',
                'cartCount' => $user->cart, // Return the updated cart count
            ]);
        }

        // For normal form submission, redirect back with a success message
        return redirect()->back()->with('success', 'Item added to cart!');
    }


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


    public function addToFavorites(Request $request, $menuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if the item is already in the user's favorites
        if ($user->favoriteItems()->where('menu_id', $menuId)->exists()) {
            // Remove from favorites if already present
            $user->favoriteItems()->detach($menuId);
            $user->decrement('favorites');
        } else {
            // Add to favorites if not present
            $user->favoriteItems()->attach($menuId);
            $user->increment('favorites');
        }

        return redirect()->back()->with('success', 'Item added to favorites!');
    }

    public function favorites(Request $request)
    {
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;
        $userFavorites = $user->favoriteItems()->count();

        // Retrieve favorite menus, excluding items already in the cart
        if ($selectedCategory == 'All Menus') {
            $menus = $user->favoriteItems()
                ->whereNotIn('menus.id', $user->cartItems->pluck('id'))
                ->get();
        } else {
            $menus = $user->favoriteItems()
                ->where('menus.category', $selectedCategory)
                ->whereNotIn('menus.id', $user->cartItems->pluck('id'))
                ->get();
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

        return redirect()->route('user.shoppingCart')->with('success', 'Item removed from cart!');
    }



    public function order()
    {
        /** @var User $user */
        $user = Auth::user();

        // Fetch the user's cart items with pivot data (quantity) and menu details (price, name, image)
        $menus = $user->cartItems()->withPivot('quantity')->get();

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

        // Pass the menu item and user to the view
        return view('user.orderView', compact('menu', 'user'));
    }
    public function menuDetails($id)
    {
        // Fetch the single menu item based on the provided ID
        $menu = Menu::findOrFail($id);

        return view('user.menuDetails', compact('menu'));
    }


    // public function menuDetailsOrder($id)
    // {
    //     $user = Auth::user();
    //     $menu = Menu::find($id);

    //     if (!$menu) {
    //         return redirect()->route('user.menu')->with('error', 'Menu item not found');
    //     }

    //     // Retrieve quantity from request, default to 1 if not provided
    //     $quantity = request()->input('quantity', 1);
    //     $itemTotal = $menu->price * $quantity;

    //     // Pass the menu item, user, quantity, and total to the view
    //     return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity', 'itemTotal'));
    // }

    public function menuDetailsOrder($id)
    {
        // Get the current authenticated user
        $user = Auth::user();

        // Retrieve the specific menu item by ID
        $menu = Menu::find($id);

        // Check if the menu item exists
        if (!$menu) {
            return redirect()->route('user.menu')->with('error', 'Menu item not found');
        }

        // Fetch the quantity from the request, default to 1 if not provided
        $quantity = request()->input('quantity', 1);

        // Pass the menu item, user, and quantity to the view
        return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity'));
    }







    public function orders(Request $request)
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

        return view('user.orders', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }

    public function messages(Request $request)
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

        return view('user.messages', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }




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
