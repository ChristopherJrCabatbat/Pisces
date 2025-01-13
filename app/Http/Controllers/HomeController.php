<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;
use App\Models\Home;

class HomeController extends Controller
{

    // public function home(Request $request)
    // {
    //     $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
    //         ->groupBy('category')
    //         ->get();

    //     $selectedCategory = $request->input('category', 'All Menus');

    //     // Retrieve menus with average ratings and rating counts
    //     if ($selectedCategory == 'All Menus') {
    //         $menus = Menu::all()->map(function ($menu) {
    //             $menu->rating = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->avg('rating') ?: 0; // Default to 0 if no rating
    //             $menu->ratingCount = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->count(); // Count reviews
    //             return $menu;
    //         });
    //     } else {
    //         $menus = Menu::where('category', $selectedCategory)->get()->map(function ($menu) {
    //             $menu->rating = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->avg('rating') ?: 0; // Default to 0 if no rating
    //             $menu->ratingCount = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->count(); // Count reviews
    //             return $menu;
    //         });
    //     }

    //     $latestMenus = Menu::orderBy('created_at', 'desc')->take(3)->get();

    //     // Fetch the 3 most popular menus based on order count
    //     $popularMenus = DB::table('orders')
    //         ->join('menus', 'orders.menu_name', '=', 'menus.name')
    //         ->select(
    //             'menus.id',
    //             'menus.name',
    //             'menus.image',
    //             'menus.price',
    //             'menus.description',
    //             'menus.category',
    //             DB::raw('COUNT(orders.id) as order_count')
    //         )
    //         ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.description', 'menus.category')
    //         ->orderByDesc('order_count')
    //         ->take(3)
    //         ->get()
    //         ->map(function ($menu) {
    //             // Add rating and ratingCount dynamically
    //             $menu->rating = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->avg('rating') ?: 0; // Default to 0 if no rating
    //             $menu->ratingCount = DB::table('feedback')
    //                 ->where('menu_items', 'LIKE', "%{$menu->name}%")
    //                 ->count(); // Count reviews
    //             return $menu;
    //         });

    //     // If request is AJAX, return only the menu cards HTML
    //     if ($request->ajax()) {
    //         return view('start.partials.menu_grid', compact('menus'))->render();
    //     }

    //     return view('start.home', compact('menus', 'categories', 'latestMenus', 'selectedCategory', 'popularMenus'));
    // }

    public function home(Request $request)
    {
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        // Retrieve menus with average ratings and rating counts
        if ($selectedCategory == 'All Menus') {
            $menus = Menu::all()->map(function ($menu) {
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating') ?: 0; // Default to 0 if no rating
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count(); // Count reviews
                return $menu;
            });
        } else {
            $menus = Menu::where('category', $selectedCategory)->get()->map(function ($menu) {
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating') ?: 0; // Default to 0 if no rating
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count(); // Count reviews
                return $menu;
            });
        }

        $latestMenus = Menu::orderBy('created_at', 'desc')->take(3)->get();

        // Fetch the 3 most popular menus based on order count
        $popularMenus = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select(
                'menus.id',
                'menus.name',
                'menus.image',
                'menus.price',
                'menus.description',
                'menus.category',
                DB::raw('COUNT(orders.id) as order_count')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.description', 'menus.category')
            ->orderByDesc('order_count')
            ->take(3)
            ->get()
            ->map(function ($menu) {
                $menu->rating = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->avg('rating') ?: 0; // Default to 0 if no rating
                $menu->ratingCount = DB::table('feedback')
                    ->where('menu_items', 'LIKE', "%{$menu->name}%")
                    ->count(); // Count reviews
                return $menu;
            });

        // Fetch the most recent feedback from the 'homes' table
        $feedback = Home::orderBy('created_at', 'desc')->first();

        // If request is AJAX, return only the menu cards HTML
        if ($request->ajax()) {
            return view('start.partials.menu_grid', compact('menus'))->render();
        }

        return view('start.home', compact('menus', 'categories', 'latestMenus', 'selectedCategory', 'popularMenus', 'feedback'));
    }




    public function fetchAllMenus()
    {
        // Fetch all menus
        $menus = Menu::all()->map(function ($menu) {
            // Add rating and ratingCount dynamically
            $menu->rating = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->avg('rating') ?: 0; // Default to 0 if no rating
            $menu->ratingCount = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->count(); // Count reviews
            return $menu;
        });

        // Pass menus to the view
        return view('start.partials.menu_grid', compact('menus'));
    }
}
