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

    // public function shoppingCart($menu)
    public function shoppingCart()
    {
        $user = Auth::user();
        $userCart = $user->cart;
        
        return view('user.shoppingCart', compact('userCart'));
    }

    // public function shoppingCart($menu)
    // {
    //     // Find the DERM by name
    //     $dermRecord = Derm::where('derm', $derm)->firstOrFail();

    //     // Retrieve paginated records associated with this DERM
    //     $records = Record::where('category', $derm)->paginate(3); // Adjust pagination size as needed

    //     return view('admin.dermShow', compact('dermRecord', 'records'));
    // }


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
