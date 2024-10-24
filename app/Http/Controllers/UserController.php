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
        return view('user.dashboard');
    }

    // public function menu()
    // {
    //     // Retrieve all menus
    //     $menus = Menu::all();

    //     // Retrieve distinct categories and their menu counts
    //     $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
    //         ->groupBy('category')
    //         ->get();

    //     return view('user.menu', compact('menus', 'categories'));
    // }
    public function menu(Request $request)
    {
        // Retrieve distinct categories and their menu counts
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        // Get the category selected by the user
        $selectedCategory = $request->input('category', 'All Menus'); // Default to 'All Menus' if no category is selected

        // Retrieve the menus based on the selected category
        if ($selectedCategory == 'All Menus') {
            $menus = Menu::all();
        } else {
            $menus = Menu::where('category', $selectedCategory)->get();
        }

        // Retrieve the current logged-in user's cart and favorites values
        /** @var User $user */
        $user = Auth::user();
        $userCart = $user->cart;

        return view('user.menu', compact('menus', 'categories', 'selectedCategory', 'userCart'));
    }


    public function addToCart(Request $request, $menuId)
    {
        // Retrieve the currently logged-in user
        /** @var User $user */
        $user = Auth::user();

        // Increment the 'cart' field by 1
        $user->cart += 1;

        // Save the updated user data
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Item added to cart!');
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
