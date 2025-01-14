<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\NewMenuNotification;

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;

use App\Services\MailerService;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        $query = Menu::query();
        $toastMessage = null;
        $activeFilter = 'Default view'; // Default description

        // Handle Default Filter
        if ($request->has('default') && $request->default === 'true') {
            $toastMessage = 'Default view applied. Showing all menus!';
        }

        // Apply Available Filter
        if ($request->has('mainFilter') && $request->mainFilter === 'available') {
            $query->where('availability', 'Available');
            $toastMessage = 'Successfully filtered by Availability: Available';
            $activeFilter = 'Availability: Available';
        }

        // Apply Unavailable Filter
        if ($request->has('mainFilter') && $request->mainFilter === 'unavailable') {
            $query->where('availability', 'Unavailable');
            $toastMessage = 'Successfully filtered by Availability: Unavailable';
            $activeFilter = 'Availability: Unavailable';
        }

        // Handle other filters (category, price, date, etc.)
        if ($request->has('categoryFilter') && $request->categoryFilter) {
            $query->where('category', $request->categoryFilter);
            $toastMessage = 'Successfully filtered by Category: ' . $request->categoryFilter;
            $activeFilter = 'Category: ' . $request->categoryFilter;
        }

        if ($request->has('priceFilter')) {
            $query->when($request->priceFilter === 'expensive', function ($q) {
                $q->orderBy('price', 'desc');
            })->when($request->priceFilter === 'cheap', function ($q) {
                $q->orderBy('price', 'asc');
            });

            $toastMessage = 'Successfully filtered by Price: ' . ucfirst($request->priceFilter);
            $activeFilter = 'Price: ' . ucfirst($request->priceFilter);
        }

        if ($request->has('dateFilter')) {
            $query->when($request->dateFilter === 'recent', function ($q) {
                $q->orderBy('created_at', 'desc');
            })->when($request->dateFilter === 'oldest', function ($q) {
                $q->orderBy('created_at', 'asc');
            });

            $toastMessage = 'Successfully filtered by Date: ' . ucfirst($request->dateFilter);
            $activeFilter = 'Date: ' . ucfirst($request->dateFilter);
        }

        if ($request->has('analyticsFilter')) {
            if ($request->analyticsFilter === 'best-sellers') {
                $bestSellerMenus = DB::table('orders')
                    ->select('menu_name', DB::raw('SUM(quantity) as total_quantity'))
                    ->groupBy('menu_name')
                    ->orderByDesc('total_quantity')
                    ->pluck('menu_name');

                $query->whereIn('name', $bestSellerMenus);
            } elseif ($request->analyticsFilter === 'customer-favorites') {
                $favoriteMenuIds = DB::table('favorite_items')
                    ->select('menu_id', DB::raw('COUNT(user_id) as total_favorites'))
                    ->groupBy('menu_id')
                    ->orderByDesc('total_favorites')
                    ->pluck('menu_id');

                $query->whereIn('id', $favoriteMenuIds);
            } elseif ($request->analyticsFilter === 'highest-rated') {
                $query->get()->map(function ($menu) {
                    $menu->rating = DB::table('feedback')
                        ->where('menu_items', 'LIKE', "%{$menu->name}%")
                        ->avg('rating');
                    $menu->review_count = DB::table('feedback')
                        ->where('menu_items', 'LIKE', "%{$menu->name}%")
                        ->count();
                    return $menu;
                })->sortByDesc('rating');
                $toastMessage = 'Successfully filtered by Analytics: Highest Rated';
                $activeFilter = 'Analytics: Highest Rated';
            }
        }

        // Add discounted price calculation in the `map` function
        $menus = $query->get()->map(function ($menu) {
            $menu->rating = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->avg('rating');
            $menu->review_count = DB::table('feedback')
                ->where('menu_items', 'LIKE', "%{$menu->name}%")
                ->count();

            // Calculate discounted price
            if (!is_null($menu->discount) && $menu->discount > 0) {
                $menu->discounted_price = $menu->price - ($menu->price * ($menu->discount / 100));
            } else {
                $menu->discounted_price = $menu->price;
            }

            return $menu;
        });


        // Sort menus by highest rating
        $menus = $menus->sortByDesc('rating')->values();

        $categories = DB::table('categories')->select('category')->distinct()->get();

        if (!is_null($toastMessage)) {
            session()->flash('toast', [
                'message' => $toastMessage,
                'type' => 'success',
            ]);
        }

        return view('admin.menu', compact('menus', 'categories', 'activeFilter', 'totalUnreadCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create(Request $request, UnreadMessagesController $unreadMessagesController)
    {
        // Fetch all categories and order them alphabetically by the 'category' column
        $categories = Category::orderBy('category', 'asc')->get();

        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        return view('admin.menuCreate', compact('categories', 'totalUnreadCount'));
    }

    public function menuCreateCategory()
    {
        $categories = Category::all(); // Fetch all categories

        return view('admin.menuCreateCategory', compact('categories'));
    }

    public function menuEditCategory(string $id)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories

        return view('admin.menuEdit', compact('menus', 'categories'));
    }

    public function menuUpdateCategory(Request $request, string $id)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories
        return view('admin.menuEdit', compact('menus', 'categories'));
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request, MailerService $mailerService)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu_images', 'public');
            }

            // Create a new menu entry in the database
            $menu = Menu::create([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'image' => $imagePath ?? null,
            ]);

            // Fetch all users subscribed to the newsletter
            $subscribedUsers = User::where('newsletter_subscription', true)->get();

            // Send email notifications to subscribed users
            foreach ($subscribedUsers as $user) {
                $mailerService->sendMail(
                    $user->email,
                    'New Menu Item Added: ' . $menu->name,
                    view('emails.newMenu', compact('menu', 'user'))->render()
                );
            }

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Menu item added successfully!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error creating menu or sending emails: ' . $e->getMessage());

            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to add menu item. Please try again.',
                'type' => 'error',
            ]);
        }

        return redirect()->route('admin.menu.index');
    }


    public function storeCategory(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'category' => 'required|string', // Add validation for category
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file type and size
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Store the uploaded image in the 'public/menu_images' directory
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        // Create a new menu entry in the database
        Category::create([
            'category' => $validated['category'], // Store the category
            'image' => $imagePath ?? null, // Save image path
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.menu.index')->with('success', 'Category item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        $menus = Menu::findOrFail($id);

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];
        return view('admin.menuShow', compact('menus', 'totalUnreadCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request, UnreadMessagesController $unreadMessagesController)
    {
        $menus = Menu::findOrFail($id);
        $categories = Category::all(); // Fetch all categories

        // Fetch unread message data using the UnreadMessagesController
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        return view('admin.menuEdit', compact('menus', 'categories', 'totalUnreadCount'));
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request, string $id, MailerService $mailerService)
    // {
    //     $menu = Menu::findOrFail($id);

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric|min:0',
    //         'category' => 'required|string',
    //         'description' => 'required|string',
    //         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'discount' => 'nullable|integer|min:0|max:100', // Validate discount as integer
    //     ]);

    //     try {
    //         // Handle file upload if a new image is provided
    //         if ($request->hasFile('image')) {
    //             if ($menu->image) {
    //                 Storage::delete('public/' . $menu->image);
    //             }
    //             $imagePath = $request->file('image')->store('menu_images', 'public');
    //             $menu->image = $imagePath;
    //         }

    //         // Compare the old and new discount values
    //         $oldDiscount = $menu->discount;
    //         $newDiscount = $validated['discount'] ?? 0; // Use 0 if no discount is provided

    //         // Update the menu fields
    //         $menu->update([
    //             'name' => $validated['name'],
    //             'category' => $validated['category'],
    //             'price' => $validated['price'],
    //             'discount' => $validated['discount'] ?? 0, // Default discount to 0
    //             'description' => $validated['description'],
    //             'image' => $menu->image ?? $menu->image,
    //             'availability' => $request->has('availability') && $request->availability === 'Available'
    //                 ? 'Available'
    //                 : 'Unavailable',
    //         ]);

    //         // Check if the discount has changed
    //         if ($oldDiscount != $newDiscount) {
    //             // Fetch all users subscribed to the newsletter
    //             $subscribedUsers = User::where('newsletter_subscription', true)->get();

    //             // Send email notifications to subscribed users
    //             foreach ($subscribedUsers as $user) {
    //                 $mailerService->sendMail(
    //                     $user->email,
    //                     'Discount Update on ' . $menu->name,
    //                     view('emails.discountUpdated', [
    //                         'menu' => $menu,
    //                         'user' => $user,
    //                         'oldDiscount' => $oldDiscount,
    //                         'newDiscount' => $newDiscount,
    //                     ])->render()
    //                 );
    //             }
    //         }

    //         session()->flash('toast', [
    //             'message' => 'Menu updated successfully.',
    //             'type' => 'success',
    //         ]);
    //     } catch (\Exception $e) {
    //         session()->flash('toast', [
    //             'message' => 'Failed to update menu item. Please try again.',
    //             'type' => 'error',
    //         ]);
    //     }

    //     return redirect()->route('admin.menu.index');
    // }

    public function update(Request $request, string $id, MailerService $mailerService)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            // Handle file upload if a new image is provided
            if ($request->hasFile('image')) {
                if ($menu->image) {
                    Storage::delete('public/' . $menu->image);
                }
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $menu->image = $imagePath;
            }

            // Save the old name for updating feedback
            $oldName = $menu->name;

            // Compare the old and new discount values
            $oldDiscount = $menu->discount;
            $newDiscount = $validated['discount'] ?? 0;

            // Update the menu fields
            $menu->update([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'discount' => $validated['discount'] ?? 0,
                'description' => $validated['description'],
                'image' => $menu->image ?? $menu->image,
                'availability' => $request->has('availability') && $request->availability === 'Available'
                    ? 'Available'
                    : 'Unavailable',
            ]);

            // Update the feedback table where the old name is present in menu_items
            DB::table('feedback')
                ->where('menu_items', 'LIKE', "%$oldName%")
                ->update([
                    'menu_items' => DB::raw("REPLACE(menu_items, '$oldName', '{$validated['name']}')"),
                ]);

            // Check if the discount has changed
            if ($oldDiscount != $newDiscount) {
                // Fetch all users subscribed to the newsletter
                $subscribedUsers = User::where('newsletter_subscription', true)->get();

                // Send email notifications to subscribed users
                foreach ($subscribedUsers as $user) {
                    $mailerService->sendMail(
                        $user->email,
                        'Discount Update on ' . $menu->name,
                        view('emails.discountUpdated', [
                            'menu' => $menu,
                            'user' => $user,
                            'oldDiscount' => $oldDiscount,
                            'newDiscount' => $newDiscount,
                        ])->render()
                    );
                }
            }

            session()->flash('toast', [
                'message' => 'Menu updated successfully.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => 'Failed to update menu item. Please try again.',
                'type' => 'error',
            ]);
        }

        return redirect()->route('admin.menu.index');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        try {
            $menu->delete();

            session()->flash('toast', [
                'message' => 'Menu item deleted successfully.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => 'Failed to delete menu item. Please try again.',
                'type' => 'error',
            ]);
        }

        // return redirect()->route('admin.menu.index');
        return redirect()->back();
    }
}
