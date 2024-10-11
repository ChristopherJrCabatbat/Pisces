<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    // return view('welcome');
    return view('start.home');
});
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::resource('menu', MenuController::class);


    Route::get('/delivery', [AdminController::class, 'delivery'])->name('delivery');

    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
    Route::get('/updates', [AdminController::class, 'updates'])->name('updates');
    Route::get('/monitoring', [AdminController::class, 'monitoring'])->name('monitoring');
});

// User Routes
Route::group([
    'prefix' => 'user',
    'as' => 'user.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/menu', [UserController::class, 'menu'])->name('menu');
    
});