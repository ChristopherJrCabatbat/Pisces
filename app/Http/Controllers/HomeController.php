<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $categories = Menu::select('category', DB::raw('count(*) as menu_count'))
            ->groupBy('category')
            ->get();

        $selectedCategory = $request->input('category', 'All Menus');

        if ($selectedCategory == 'All Menus') {
            $menus = Menu::all();
        } else {
            $menus = Menu::where('category', $selectedCategory)->get();
        }

        $latestMenus = Menu::orderBy('created_at', 'desc')->take(3)->get();

        // Fetch the 3 most popular menus based on order count
        $popularMenus = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.description', 'menus.category', DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.description', 'menus.category')
            ->orderByDesc('order_count')
            ->take(3)
            ->get();

        // If request is AJAX, return only the menu cards HTML
        if ($request->ajax()) {
            return view('start.partials.menu_grid', compact('menus'))->render();
        }

        return view('start.home', compact('menus', 'categories', 'latestMenus', 'selectedCategory', 'popularMenus'));
    }

    public function fetchAllMenus()
    {
        $menus = Menu::all(); // Fetch all menus
        return view('start.partials.menu_grid', compact('menus'));
    }
}
