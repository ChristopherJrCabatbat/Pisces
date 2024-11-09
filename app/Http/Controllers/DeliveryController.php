<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $menus = Delivery::paginate(2);
        $deliveries = Delivery::all();
        return view('admin.delivery', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'fullName' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'contactNumber' => 'required|string|max:20',
    //         'address' => 'required|string',
    //         'shippingMethod' => 'required|string',
    //         'paymentMethod' => 'required|string',
    //         'note' => 'nullable|string',
    //         'menu_names' => 'required|array',
    //         'quantities' => 'required|array',
    //     ]);

    //     // Prepare the order and quantity fields as comma-separated strings
    //     $orderNames = implode(', ', $request->menu_names);
    //     $orderQuantities = implode(', ', $request->quantities);

    //     // Calculate the total quantity
    //     $totalQuantity = array_sum($request->quantities);

    //     // Insert the order into the deliveries table
    //     DB::table('deliveries')->insert([
    //         'name' => $request->input('fullName'),
    //         'email' => $request->input('email'),
    //         'contact_number' => $request->input('contactNumber'),
    //         'order' => $orderNames,
    //         'address' => $request->input('address'),
    //         'quantity' => $orderQuantities,
    //         'shipping_method' => $request->input('shippingMethod'),
    //         'mode_of_payment' => $request->input('paymentMethod'),
    //         'note' => $request->input('note'),
    //         'status' => 'Pending',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     /** @var User $user */
    //     $user = Auth::user();

    //     // Remove all cart items for the logged-in user
    //     DB::table('cart_items')->where('user_id', $user->id)->delete();

    //     // Reset the user's 'cart' field to 0
    //     $user->cart = 0;
    //     $user->save();

    //     return redirect()->route('user.shoppingCart')->with('success', 'Your order has been placed successfully!');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactNumber' => 'required|string|max:20',
            'address' => 'required|string',
            'shippingMethod' => 'required|string',
            'paymentMethod' => 'required|string',
            'note' => 'nullable|string',
            'menu_names' => 'required|array',
            'quantities' => 'required|array',
        ]);
    
        $orderItems = [];
        $totalQuantity = 0;
        $orderQuantities = implode(', ', $request->quantities);
    
        // Construct the order string
        foreach ($request->menu_names as $index => $menuName) {
            $quantity = $request->quantities[$index];
            $orderItems[] = "{$menuName} (x{$quantity})";
            $totalQuantity += $quantity;
        }
    
        $orderString = implode(', ', $orderItems);
    
        // Insert the order into the deliveries table
        $deliveryId = DB::table('deliveries')->insertGetId([
            'name' => $request->input('fullName'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contactNumber'),
            'order' => $orderString,
            'address' => $request->input('address'),
            'quantity' => $orderQuantities,
            'shipping_method' => $request->input('shippingMethod'),
            'mode_of_payment' => $request->input('paymentMethod'),
            'note' => $request->input('note'),
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Store each order item in the orders table
        foreach ($request->menu_names as $index => $menuName) {
            $quantity = $request->quantities[$index];
            DB::table('orders')->insert([
                'delivery_id' => $deliveryId,
                'menu_name' => $menuName,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        /** @var User $user */
        $user = Auth::user();
    
        // Remove all cart items for the logged-in user
        DB::table('cart_items')->where('user_id', $user->id)->delete();
    
        // Reset the user's 'cart' field to 0
        $user->cart = 0;
        $user->save();
    
        return redirect()->route('user.shoppingCart')->with('success', 'Your order has been placed successfully!');
    }
    



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
