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
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PromotionsController;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('start.home');
// });

// Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/menus/all', [HomeController::class, 'fetchAllMenus'])->name('menus.all');

Route::get('/welcome', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'Admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'User') {
        return redirect('/user/dashboard');
    }

    abort(403, 'Unauthorized access'); // Handle unexpected roles or unauthorized access
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::post('/clear-promotions-session', function () {
    session()->forget(['availablePromotions', 'promotions_shown_during_session']);
    return response()->json(['message' => 'Promotions cleared']);
})->middleware('auth');

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    // Route::get('/test-email', function () {
    //     Mail::raw('Pre pa-check nga kung gumagana.', function ($message) {
    //         $message->to('zhyryllposadas@gmail.com')
    //             ->subject('Test Email');
    //     });

    //     return 'Email sent successfully!';
    // })->name('test-email');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::put('/userUpdate', [AdminController::class, 'userUpdate'])->name('userUpdate');
    Route::delete('/userDestroy/{id}', [AdminController::class, 'userDestroy'])->name('userDestroy');
    Route::post('/saveFeedback', [AdminController::class, 'saveFeedback'])->name('saveFeedback');

    // Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::resource('menu', MenuController::class);
    Route::get('/menuSearch', [MenuController::class, 'menuSearch'])->name('menuSearch');
    Route::get('/menuCreateCategory', [MenuController::class, 'menuCreateCategory'])->name('menuCreateCategory');
    Route::get('/menuEditCategory', [MenuController::class, 'menuEditCategory'])->name('menuEditCategory');
    Route::post('/storeCategory', [MenuController::class, 'storeCategory'])->name('storeCategory');

    Route::resource('category', CategoryController::class);

    Route::resource('rider', RiderController::class);

    Route::resource('delivery', DeliveryController::class);
    Route::put('/updateStatus/{id}', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/assignRider', [DeliveryController::class, 'assignRider'])->name('assignRider');
    Route::get('/deliveryDetails/{id}', [DeliveryController::class, 'deliveryDetails'])->name('deliveryDetails');
    Route::get('/deliveryCreateRider', [DeliveryController::class, 'deliveryCreateRider'])->name('deliveryCreateRider');
    Route::post('/storeRider', [DeliveryController::class, 'storeRider'])->name('storeRider');

    Route::resource('promotions', PromotionsController::class);
    Route::post('/promotions/{promotion}/toggleAvailability', [PromotionsController::class, 'toggleAvailability'])->name('toggleAvailability');


    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
    Route::put('/updateSentiment/{id}', [FeedbackController::class, 'updateSentiment'])->name('updateSentiment');
    Route::post('/respondFeedback', [AdminController::class, 'respondFeedback'])->name('respondFeedback');


    Route::get('/customerMessages', [AdminController::class, 'customerMessages'])->name('customerMessages');
    Route::get('/monitoring', [AdminController::class, 'monitoring'])->name('monitoring');

    Route::get('/updates', [AdminController::class, 'updates'])->name('updates');
    Route::get('/viewOrders/{id}', [AdminController::class, 'viewOrders'])->name('viewOrders');
    Route::get('/getOrderDetails/{id}', [AdminController::class, 'getOrderDetails'])->name('getOrderDetails');


    // Route::get('/messageUser', [AdminController::class, 'messageUser'])->name('messageUser');
    Route::get('/messageUser/{id}', [AdminController::class, 'messageUser'])->name('messageUser');
    Route::post('/messageUser/{userId}/send', [AdminController::class, 'sendMessage'])->name('sendMessage');
    Route::post('/markAsRead/{userId}', [AdminController::class, 'markMessagesAsRead'])->name('markAsRead');
});

// User Routes
Route::group([
    'prefix' => 'user',
    'as' => 'user.',
    'middleware' => ['auth', 'verified', 'login'],
], function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::put('/userUpdate', [UserController::class, 'userUpdate'])->name('userUpdate');
    Route::post('/submitExperience', [UserController::class, 'submitExperience'])->name('submitExperience');


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
    // Route::match(['get', 'post'], '/order', [UserController::class, 'order']);
    Route::get('/orderView/{id}', [UserController::class, 'orderView'])->name('orderView');

    // Route::post('/menuDetails/{id}', [UserController::class, 'menuDetails'])->name('menuDetails');
    Route::get('/menuDetails/{id}', [UserController::class, 'menuDetails'])->name('menuDetails');
    Route::get('/menuDetailsOrder/{id}', [UserController::class, 'menuDetailsOrder'])->name('menuDetailsOrder');

    Route::get('/orders', [UserController::class, 'orders'])->name('orders');

    Route::get('/messages', [UserController::class, 'messages'])->name('messages');
    Route::get('/messagesPisces', [UserController::class, 'messagesPisces'])->name('messagesPisces');
    Route::post('/messageUser/{userId}/send', [UserController::class, 'sendMessage'])->name('sendMessage');
    Route::post('/markAsRead/{userId}', [UserController::class, 'markMessagesAsRead'])->name('markAsRead');

    Route::get('/shopUpdates', [UserController::class, 'shopUpdates'])->name('shopUpdates');
    Route::get('/trackOrder/{delivery}', [UserController::class, 'trackOrder'])->name('trackOrder');
    Route::get('/reviewOrder/{delivery}', [UserController::class, 'reviewOrder'])->name('reviewOrder');

    Route::resource('delivery', DeliveryController::class);
    Route::post('/orderStore', [DeliveryController::class, 'orderStore'])->name('orderStore');
    Route::post('/reviewOrderStore', [DeliveryController::class, 'reviewOrderStore'])->name('reviewOrderStore');
    // Route::post('/sendMessage', [DeliveryController::class, 'sendMessage'])->name('sendMessage');
    Route::get('/orderRepeat/{deliveryId}', [UserController::class, 'orderRepeat'])->name('orderRepeat');

    Route::resource('feedback', FeedbackController::class);

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
