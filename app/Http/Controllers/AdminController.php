<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
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
