<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Delivery;
use App\Models\Rider;
use App\Models\Menu;
use App\Models\Message;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::orderBy('created_at', 'desc')->get();
        $riders = Rider::all(); // Assuming you have a Rider model

        foreach ($deliveries as $delivery) {
            $orders = explode(', ', $delivery->order);
            $menuImages = Menu::whereIn('name', $orders)->pluck('image', 'name')->toArray();
            $delivery->menu_images = $menuImages;
        }

        return view('admin.delivery', compact('deliveries', 'riders'));
    }

    public function deliveryCreateRider()
    {
        return view('admin.deliveryCreateRider');
    }
    public function storeRider(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        // Create a new menu entry in the database
        Rider::create([
            'name' => $validated['name'], // Store the category

        ]);

        // Redirect back with a success message
        return redirect()->route('admin.delivery.index')->with('success', 'Rider added successfully.');
    }

    // public function updateStatus(Request $request, string $id)
    // {
    //     // Validate the new status
    //     $validatedData = $request->validate([
    //         'status' => 'required|string|in:Pending,Preparing,Out for Delivery,Delivered,Returned',
    //     ]);

    //     // Define allowed transitions
    //     $allowedTransitions = [
    //         'Pending' => ['Preparing'],
    //         'Preparing' => ['Out for Delivery'],
    //         'Out for Delivery' => ['Delivered'],
    //         'Delivered' => ['Returned'],
    //         'Returned' => [],
    //     ];

    //     // Find the delivery by ID
    //     $delivery = Delivery::findOrFail($id);

    //     // Check if the new status is valid for the current status
    //     if (!in_array($validatedData['status'], $allowedTransitions[$delivery->status])) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid status transition.',
    //         ]);
    //     }

    //     // Update the status
    //     $delivery->status = $validatedData['status'];

    //     if ($delivery->save()) {
    //         // Notify the user of the status change
    //         $this->notifyUserOfStatusChange($delivery, $validatedData['status']);

    //         // Get the new valid statuses for the updated status
    //         $newAllowedStatuses = array_merge([$delivery->status], $allowedTransitions[$delivery->status]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => "Delivery status changed to {$validatedData['status']}.",
    //             'allowedStatuses' => $newAllowedStatuses,
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Failed to update delivery status.',
    //     ]);
    // }


    public function updateStatus(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Preparing,Out for Delivery,Delivered,Returned',
        ]);
    
        $allowedTransitions = [
            'Pending' => ['Preparing'],
            'Preparing' => ['Out for Delivery'],
            'Out for Delivery' => ['Delivered'],
            'Delivered' => ['Returned'],
            'Returned' => [],
        ];
    
        $delivery = Delivery::findOrFail($id);
    
        if (!in_array($validatedData['status'], $allowedTransitions[$delivery->status])) {
            return response()->json(['success' => false, 'message' => 'Invalid status transition.']);
        }
    
        $delivery->status = $validatedData['status'];
        $delivery->save();
    
        $validStatuses = array_merge(
            [$delivery->status],
            $allowedTransitions[$delivery->status]
        );
    
        $showModal = $validatedData['status'] === 'Out for Delivery';
    
        return response()->json([
            'success' => true,
            'message' => "Delivery status updated to {$validatedData['status']}.",
            'showModal' => $showModal,
            'deliveryId' => $delivery->id,
            'validStatuses' => $validStatuses,
            'currentStatus' => $delivery->status,
        ]);
    }
    
    

    public function assignRider(Request $request)
    {
        $validatedData = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'rider' => 'required|string',
        ]);
    
        $delivery = Delivery::findOrFail($validatedData['delivery_id']);
        $delivery->rider = $validatedData['rider'];
        $delivery->save();
    
        return response()->json(['success' => true, 'message' => 'Rider assigned successfully.']);
    }
    




    private function notifyUserOfStatusChange(Delivery $delivery, $newStatus)
    {
        // Check if the delivery has an associated user (for sending the message)
        $user = User::where('email', $delivery->email)->first();

        if (!$user) {
            return;
        }

        // Define status messages
        $statusMessages = [
            'Pending' => 'Your order is pending.',
            'Preparing' => 'Your order is now being prepared.',
            'Out for Delivery' => 'Your order is now out for delivery.',
            'Delivered' => 'Your order has been delivered.',
            'Returned' => 'Your order has been returned.',
        ];

        // Get the message text for the new status
        $messageText = $statusMessages[$newStatus] ?? 'Your order status has been updated.';

        // Create the message
        Message::create([
            'user_id' => Auth::id(), // Admin is the sender
            'receiver_id' => $user->id, // The customer is the recipient
            'sender_role' => 'Admin',
            'message_text' => $messageText,
        ]);
    }






    public function deliveryUpdate(Request $request)
    {
        // Validate the request if needed

        $delivery = Delivery::findOrFail($request->input('id'));
        $delivery->status = $request->input('status');
        $delivery->save();

        return response()->json(['success' => true]);
    }


    public function deliveryDetails($id)
    {
        // Find the delivery record by ID or fail with a 404
        $delivery = Delivery::findOrFail($id);

        // Split orders to extract menu names
        $orders = explode(', ', $delivery->order);

        // Remove the quantity part (e.g., (x5)) from each menu name
        $plainMenuNames = array_map(function ($order) {
            return preg_replace('/\s*\(x\d+\)$/', '', $order); // Remove (x5), (x4), etc.
        }, $orders);

        // Fetch the menu images using the cleaned menu names
        $menuImages = Menu::whereIn('name', $plainMenuNames)
            ->pluck('image', 'name')
            ->map(function ($image) {
                return $image ? asset('storage/' . $image) : asset('images/logo.jpg'); // Use fallback for missing images
            })
            ->toArray();


        return response()->json([
            'name' => $delivery->name,
            'email' => $delivery->email,
            'contact_number' => $delivery->contact_number,
            'address' => $delivery->address,
            'shipping_method' => $delivery->shipping_method,
            'mode_of_payment' => $delivery->mode_of_payment,
            'note' => $delivery->note,
            'order' => $delivery->order,
            'quantity' => $delivery->quantity,
            'status' => $delivery->status,
            'menu_images' => $menuImages, // Include menu images in the response
        ]);
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
            'total_price' => $request->input('total_price'),
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

        // Add toast message to session
        session()->flash('toast', [
            'message' => 'Order placed successfully!',
            'type' => 'success',
        ]);

        return redirect()->route('user.menu');
    }



    public function orderStore(Request $request)
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
        $totalPrice = 0; // Initialize total price
        $orderQuantities = implode(', ', $request->quantities);

        // Construct the order string and calculate total price
        foreach ($request->menu_names as $index => $menuName) {
            $quantity = $request->quantities[$index];
            $menu = Menu::where('name', $menuName)->firstOrFail();
            $itemTotal = $menu->price * $quantity; // Calculate total for each item
            $orderItems[] = "{$menuName} (x{$quantity})";
            $totalQuantity += $quantity;
            $totalPrice += $itemTotal; // Add to total price
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
            'total_price' => $totalPrice, // Save total price
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

        // Add toast message to session
        session()->flash('toast', [
            'message' => 'Order placed successfully!',
            'type' => 'success',
        ]);

        return redirect()->route('user.menu');
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
