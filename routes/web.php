<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;

// Route::get('/', function () {
//     return view('start.home');
// });

Route::get('/', [HomeController::class, 'home'])->name('home');

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

require __DIR__ . '/auth.php';

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::resource('menu', MenuController::class);
    Route::get('/menuSearch', [MenuController::class, 'menuSearch'])->name('menuSearch');
    Route::get('/menuCreateCategory', [MenuController::class, 'menuCreateCategory'])->name('menuCreateCategory');
    Route::get('/menuEditCategory', [MenuController::class, 'menuEditCategory'])->name('menuEditCategory');
    Route::post('/storeCategory', [MenuController::class, 'storeCategory'])->name('storeCategory');

    Route::resource('category', CategoryController::class);

    Route::resource('delivery', DeliveryController::class);
    Route::put('/updateStatus/{id}', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/deliveryDetails/{id}', [DeliveryController::class, 'deliveryDetails'])->name('deliveryDetails');
    Route::get('/deliveryCreateRider', [DeliveryController::class, 'deliveryCreateRider'])->name('deliveryCreateRider');
    Route::post('/storeRider', [DeliveryController::class, 'storeRider'])->name('storeRider');

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

    Route::get('/menuView/{id}', [UserController::class, 'menuView'])->name('menuView');
    Route::put('addToCart/{id}', [UserController::class, 'addToCart'])->name('addToCart');
    Route::put('addToCartModal/{id}', [UserController::class, 'addToCartModal'])->name('addToCartModal');
    Route::put('addToFavorites/{id}', [UserController::class, 'addToFavorites'])->name('addToFavorites');

    Route::get('/shoppingCart', [UserController::class, 'shoppingCart'])->name('shoppingCart');
    Route::post('/updateQuantity', [UserController::class, 'updateQuantity'])->name('updateQuantity');
    Route::delete('/removeCart/{cartItemId}', [UserController::class, 'removeCart'])->name('removeCart');

    Route::get('/favorites', [UserController::class, 'favorites'])->name('favorites');

    Route::post('/order', [UserController::class, 'order'])->name('order');
    Route::get('/orderView/{id}', [UserController::class, 'orderView'])->name('orderView');

    // Route::post('/menuDetails/{id}', [UserController::class, 'menuDetails'])->name('menuDetails');
    Route::get('/menuDetails/{id}', [UserController::class, 'menuDetails'])->name('menuDetails');
    Route::get('/menuDetailsOrder/{id}', [UserController::class, 'menuDetailsOrder'])->name('menuDetailsOrder');

    Route::get('/orders', [UserController::class, 'orders'])->name('orders');

    Route::get('/messages', [UserController::class, 'messages'])->name('messages');
    Route::get('/messagesPisces', [UserController::class, 'messagesPisces'])->name('messagesPisces');
    Route::get('/shopUpdates', [UserController::class, 'shopUpdates'])->name('shopUpdates');
    Route::get('/trackOrder', [UserController::class, 'trackOrder'])->name('trackOrder');
    Route::get('/reviewOrder', [UserController::class, 'reviewOrder'])->name('reviewOrder');

    Route::resource('delivery', DeliveryController::class);
    Route::post('/orderStore', [DeliveryController::class, 'orderStore'])->name('orderStore');

    // Route for viewing menu details
    Route::get('/menu-detail/{menuId}', [UserController::class, 'menuDetail'])->name('menuDetail');
});

// User Routes
Route::group([
    'prefix' => 'rider',
    'as' => 'rider.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    Route::get('/dashboard', [RiderController::class, 'dashboard'])->name('dashboard');

});
