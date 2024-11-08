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

    return view('user.dashboard', compact('userCart', 'userFavorites', 'topCategories', 'latestMenus'));
}




    public function menu(Request $request)
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

        return view('user.menu', compact('menus', 'categories', 'selectedCategory', 'userCart', 'user', 'userFavorites'));
    }


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
