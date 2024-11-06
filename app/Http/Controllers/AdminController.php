<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('userCount', 'deliveryCount', 'menuCount', 'categoryCount'));
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
