<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Delivery;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount = User::where('role', 'User')->count();
        $deliveryCount = Delivery::count();
        $menuCount = Menu::count();
        $categoryCount = Category::count();
    
        // Fetch the top 5 most popular menus based on the total order count
        $topPicks = DB::table('orders')
            ->join('menus', 'orders.menu_name', '=', 'menus.name')
            ->select(
                'menus.id',
                'menus.name',
                'menus.image',
                'menus.category',
                'menus.price',
                'menus.description',
                DB::raw('SUM(orders.quantity) as total_order_count')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.category', 'menus.price', 'menus.description')
            ->orderByDesc('total_order_count')
            ->take(5)
            ->get();
    
        return view('admin.dashboard', compact('userCount', 'deliveryCount', 'menuCount', 'categoryCount', 'topPicks'));
    }    


    public function menu()
    {
        return view('admin.menu');
    }

    public function delivery()
    {
        return view('admin.delivery');
    }

    public function customers()
    {
        return view('admin.customers');
    }
    public function feedback()
    {
        return view('admin.feedback');
    }
    public function updates()
    {
        return view('admin.updates');
    }
    public function monitoring()
    {
        return view('admin.monitoring');
    }
}
