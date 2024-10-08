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
    
    public function users()
    {
        return view('admin.users');
    }
}
