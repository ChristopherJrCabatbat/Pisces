<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade

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

        return view('user.menu', compact('menus', 'categories', 'selectedCategory'));
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
