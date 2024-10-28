<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;

class UserController extends Controller
{
    public function dashboard()
    {
        // Retrieve the current logged-in user's cart and favorites values
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;

        return view('user.dashboard', compact('userCart'));
    }


    public function menu(Request $request)
    {
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        $user = Auth::user();
        $userCart = $user->cart;

        // Retrieve menus based on selected category, excluding items in the cart
        if ($selectedCategory == 'All Menus') {
            $menus = Menu::whereNotIn('id', $user->cartItems->pluck('id'))->get();
        } else {
            $menus = Menu::where('category', $selectedCategory)
                ->whereNotIn('id', $user->cartItems->pluck('id'))
                ->get();
        }

        return view('user.menu', compact('menus', 'categories', 'selectedCategory', 'userCart'));
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

        // Get menus added to cart by the current user along with their cart_items IDs
        $menus = DB::table('menus')
            ->join('cart_items', 'menus.id', '=', 'cart_items.menu_id')
            ->where('cart_items.user_id', $user->id)
            ->select('menus.*', 'cart_items.id as cart_item_id')
            ->get();

        return view('user.shoppingCart', compact('user', 'menus'));
    }



    // public function removeCart($menuId)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Check if the cart item exists for the current user and specified menu_id
    //     $cartItem = DB::table('cart_items')
    //         ->where('user_id', $user->id)
    //         ->where('menu_id', $menuId)
    //         ->first();

    //     if ($cartItem) {
    //         // Delete the cart item for this user and menu_id
    //         DB::table('cart_items')
    //             ->where('user_id', $user->id)
    //             ->where('menu_id', $menuId)
    //             ->delete();

    //         // Decrement the user's cart count
    //         $user->decrement('cart');

    //         return redirect()->route('user.shoppingCart')->with('success', 'Item removed from cart!');
    //     }

    //     return redirect()->route('user.shoppingCart')->with('error', 'Item not found in cart.');
    // }

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


    public function addToFavorites($menuId)
    {
        $userId = Auth::id();

        // Increment the user's favorites count in the database
        DB::table('users')->where('id', $userId)->increment('favorites_count');

        return response()->json(['success' => true, 'message' => 'Item added to favorites']);
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
