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

    //  public function index()
    //  {
    //      $deliveries = Delivery::orderBy('created_at', 'desc')->get(); // Fetch all deliveries
    //      $riders = Rider::all(); // Assuming you have a Rider model

    //      foreach ($deliveries as $delivery) {
    //          $orders = explode(', ', $delivery->order);
    //          $menuImages = Menu::whereIn('name', $orders)->pluck('image', 'name')->toArray();
    //          $delivery->menu_images = $menuImages;
    //      }

    //      return view('admin.delivery', compact('deliveries', 'riders'));
    //  }

    public function index(UnreadMessagesController $unreadMessagesController)
    {
        // Fetch unread message data
        $unreadMessageData = $unreadMessagesController->getUnreadMessageData();
        $totalUnreadCount = $unreadMessageData['totalUnreadCount'];

        // Count deliveries with specified statuses
        $deliveryBadgeCount = Delivery::whereIn('status', [
            'Pending GCash Transaction',
            'Pending',
            'Preparing',
            'Out for Delivery'
        ])->count();

        // Fetch all deliveries
        $deliveries = Delivery::orderBy('created_at', 'desc')->get();

        // Fetch all riders
        $riders = Rider::all(); // Assuming you have a Rider model

        // Process deliveries to include menu images
        foreach ($deliveries as $delivery) {
            $orders = explode(', ', $delivery->order);
            $menuImages = Menu::whereIn('name', $orders)->pluck('image', 'name')->toArray();
            $delivery->menu_images = $menuImages;
        }

        // Pass variables to the view
        return view('admin.delivery', compact('deliveries', 'riders', 'totalUnreadCount', 'deliveryBadgeCount'));
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


    public function updateStatus(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Preparing,Out for Delivery,Delivered,Returned',
        ]);

        $allowedTransitions = [
            'Pending GCash Transaction' => ['Pending'],
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
            'shipping_fee' => $delivery->shipping_fee,
            'total_price' => $delivery->total_price,
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
        $validatedData = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactNumber' => 'required|string|max:20',
            'house_number' => 'nullable|string',
            'barangay' => 'required|string',
            'purok' => 'nullable|string',
            'shipping_fee' => 'required|string',
            'paymentMethod' => 'required|string',
            'note' => 'nullable|string',
            'menu_names' => 'required|array',
            'quantities' => 'required|array',
            'total_price' => 'required|numeric',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->increment('order_count');

            $orderItems = [];
            $totalQuantity = 0;
            $totalPrice = 0;

            foreach ($request->menu_names as $index => $menuName) {
                $quantity = $request->quantities[$index];
                $menu = Menu::where('name', $menuName)->firstOrFail();
                $menuDiscountedPrice = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;
                $itemTotal = $menuDiscountedPrice * $quantity;

                $orderItems[] = "{$menuName} (x{$quantity})";
                $totalQuantity += $quantity;
                $totalPrice += $itemTotal;
            }

            // Add the shipping fee to the total price
            $shippingFee = (float)$request->input('shipping_fee');
            $totalPrice += $shippingFee;

            if ($user->has_discount) {
                $totalPrice = ($totalPrice * 0.95) + 5;
                $user->update(['has_discount' => false]);
            }

            $totalPrice = round($totalPrice);
            $orderString = implode(', ', $orderItems);
            $status = $request->input('paymentMethod') === 'GCash' ? 'Pending GCash Transaction' : 'Pending';

            $fullAddress = (!empty($request->house_number) ? "#{$request->house_number} " : "") .
                "Barangay {$request->barangay}" .
                ($request->filled('purok') ? " Purok {$request->purok}" : "") .
                " San Carlos City, Pangasinan";

            $deliveryId = DB::table('deliveries')->insertGetId([
                'name' => $request->input('fullName'),
                'email' => $request->input('email'),
                'contact_number' => $request->input('contactNumber'),
                'order' => $orderString,
                'address' => $fullAddress,
                'quantity' => implode(', ', $request->quantities),
                'shipping_fee' => $request->input('shipping_fee'),
                'mode_of_payment' => $request->input('paymentMethod'),
                'note' => $request->input('note'),
                'status' => $status,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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

            // Clear the user's cart after placing the order
            DB::table('cart_items')->where('user_id', $user->id)->delete();
            $user->cart = 0;
            $user->save();

            // Update the user's last_order field
            $user->update(['last_order' => now()]);

            // Set modal session key if the order count is divisible by 7
            if ($user->order_count % 1 === 0) {
                session()->put('showExperienceModal', true); // Persistent session key
            }

            // Handle GCash payment
            if ($request->input('paymentMethod') === 'GCash') {
                // Prepare GCash message
                $messageText = "Please complete your GCash transaction. Kindly send the payment for the following orders: {$orderString} with a total of ₱{$totalPrice}. Notify us once done. GCash Account: Goddard Gabriel Manese. GCash Number: 0945 839 3794.";

                // Save the message
                DB::table('messages')->insert([
                    'user_id' => $user->id,
                    'receiver_id' => '1', // Assuming the user receives the message
                    'sender_role' => 'System',
                    'message_text' => $messageText,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Redirect to messagesPisces with success toast
                session()->flash('toast', [
                    'message' => 'Order placed successfully! Please check your messages to complete the GCash payment.',
                    'type' => 'success',
                ]);

                return redirect()->route('user.messagesPisces');
            }

            session()->flash('toast', [
                'message' => 'Order placed successfully! You can monitor your order in the Orders section.',
                'type' => 'success',
            ]);

            return redirect()->route('user.menu');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to place the order. ' . $e->getMessage());
        }
    }

    public function reviewOrderStore(Request $request)
    {
        $validatedData = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactNumber' => 'required|string|max:20',
            'house_number' => 'nullable|string',
            'barangay' => 'required|string',
            'purok' => 'nullable|string',
            'shipping_fee' => 'required|numeric', // Ensure shipping_fee is numeric
            'paymentMethod' => 'required|string',
            'note' => 'nullable|string',
            'menu_names' => 'required|array',
            'quantities' => 'required|array',
            'total_price' => 'required|numeric',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->increment('order_count');

            $orderItems = [];
            $totalQuantity = 0;
            $totalPrice = 0;

            foreach ($request->menu_names as $index => $menuName) {
                $quantity = $request->quantities[$index];
                $menu = Menu::where('name', $menuName)->firstOrFail();
                $menuDiscountedPrice = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;
                $itemTotal = $menuDiscountedPrice * $quantity;

                $orderItems[] = "{$menuName} (x{$quantity})";
                $totalQuantity += $quantity;
                $totalPrice += $itemTotal;
            }

            // Apply user discount if applicable
            if ($user->has_discount) {
                $totalPrice *= 0.95;
                $user->update(['has_discount' => false]);
            }

            // Add shipping fee to total price
            $shippingFee = $request->input('shipping_fee');
            $totalPrice += $shippingFee; // Include shipping fee
            $totalPrice = round($totalPrice);

            $orderString = implode(', ', $orderItems);
            $status = $request->input('paymentMethod') === 'GCash' ? 'Pending GCash Transaction' : 'Pending';

            $fullAddress = (!empty($request->house_number) ? "#{$request->house_number} " : "") .
                "Barangay {$request->barangay}" .
                ($request->filled('purok') ? " Purok {$request->purok}" : "") .
                " San Carlos City, Pangasinan";

            $deliveryId = DB::table('deliveries')->insertGetId([
                'name' => $request->input('fullName'),
                'email' => $request->input('email'),
                'contact_number' => $request->input('contactNumber'),
                'order' => $orderString,
                'address' => $fullAddress,
                'quantity' => implode(', ', $request->quantities),
                'shipping_fee' => $shippingFee,
                'mode_of_payment' => $request->input('paymentMethod'),
                'note' => $request->input('note'),
                'status' => $status,
                'total_price' => $totalPrice, // Save the total price with shipping fee
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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

            // Update the user's last_order field
            $user->update(['last_order' => now()]);

            // Set modal session key if the order count is divisible by 7
            if ($user->order_count % 1 === 0) {
                session()->put('showExperienceModal', true);
            }

            // Handle GCash payment
            if ($request->input('paymentMethod') === 'GCash') {
                $messageText = "Please complete your GCash transaction. Kindly send the payment for the following orders: {$orderString} with a total of ₱{$totalPrice}. Notify us once done. GCash Account: Goddard Gabriel Manese. GCash Number: 0945 839 3794.";

                DB::table('messages')->insert([
                    'user_id' => $user->id,
                    'receiver_id' => '1',
                    'sender_role' => 'System',
                    'message_text' => $messageText,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                session()->flash('toast', [
                    'message' => 'Order placed successfully! Please check your messages to complete the GCash payment.',
                    'type' => 'success',
                ]);

                return redirect()->route('user.messagesPisces');
            }

            session()->flash('toast', [
                'message' => 'Order placed successfully! You can monitor your order in the Orders section.',
                'type' => 'success',
            ]);

            return redirect()->route('user.menu');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to place the order. ' . $e->getMessage());
        }
    }



    public function orderStore(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Order Store Request:', $request->all());

        // Validate incoming request
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactNumber' => 'required|string|max:20',
            'house_number' => 'nullable|string',
            'barangay' => 'required|string',
            'purok' => 'nullable|string',
            'paymentMethod' => 'required|string',
            'note' => 'nullable|string',
            'menu_names' => 'required|array',
            'quantities' => 'required|array',
            'total_price' => 'required|numeric',
            'shipping_fee' => 'required', // Ensure the fee is numeric and greater than 0
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->increment('order_count'); // Increment the user's order count

            $orderItems = [];
            $totalQuantity = 0;
            $totalPrice = 0;
            $orderQuantities = implode(', ', $request->quantities);

            // Calculate item totals and overall total price
            foreach ($request->menu_names as $index => $menuName) {
                $quantity = $request->quantities[$index];
                $menu = Menu::where('name', $menuName)->firstOrFail();

                $menuDiscountedPrice = $menu->discount > 0
                    ? round($menu->price * (1 - $menu->discount / 100), 2)
                    : $menu->price;

                $itemTotal = $menuDiscountedPrice * $quantity;
                $orderItems[] = "{$menuName} (x{$quantity})";
                $totalQuantity += $quantity;
                $totalPrice += $itemTotal;
            }

            // Apply user-specific discount if applicable
            $hasDiscount = $user->has_discount;
            if ($hasDiscount) {
                $totalPrice *= 0.95; // Apply a 5% discount
                $user->update(['has_discount' => false]); // Reset discount eligibility
            }

            // Round the total price to the nearest whole number
            $totalPrice = round($totalPrice);

            // Add the shipping fee to the final total price
            $shippingFee = (float)$request->input('shipping_fee'); // Cast to float to ensure it's numeric
            Log::info('Parsed Shipping Fee:', ['Shipping fee' => $shippingFee]); // Debug log for shipping fee

            // $shippingFee = $request->input('shipping_fee');
            $finalPrice = $totalPrice + $shippingFee;

            $orderString = implode(', ', $orderItems);
            $status = $request->input('paymentMethod') === 'GCash' ? 'Pending GCash Transaction' : 'Pending';

            // Construct the full address
            $fullAddress = '';
            if (!empty($request->house_number)) {
                $fullAddress .= "#{$request->house_number} ";
            }
            $fullAddress .= "Barangay {$request->barangay}";
            if ($request->filled('purok')) {
                $fullAddress .= " Purok {$request->purok}";
            }
            $fullAddress .= " San Carlos City, Pangasinan";

            // Insert delivery data into the database
            $deliveryId = DB::table('deliveries')->insertGetId([
                'name' => $request->input('fullName'),
                'email' => $request->input('email'),
                'contact_number' => $request->input('contactNumber'),
                'order' => $orderString,
                'address' => $fullAddress,
                'quantity' => $orderQuantities,
                'mode_of_payment' => $request->input('paymentMethod'),
                'note' => $request->input('note'),
                'status' => $status,
                'total_price' => $finalPrice,
                'shipping_fee' => $shippingFee,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert individual menu items into the orders table
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

            // Update the user's last_order field
            $user->update(['last_order' => now()]);

            // Set modal session key if the order count is divisible by 7
            if ($user->order_count % 1 === 0) {
                session()->put('showExperienceModal', true); // Persistent session key
            }

            // Handle GCash payment
            if ($request->input('paymentMethod') === 'GCash') {
                // Prepare GCash message
                $messageText = "Please complete your GCash transaction. Kindly send the payment for the following orders: {$orderString} with a total of ₱{$finalPrice}. Notify us once done. GCash Account: Goddard Gabriel Manese. GCash Number: 0945 839 3794.";

                // Save the message
                DB::table('messages')->insert([
                    'user_id' => $user->id,
                    'receiver_id' => '1', // Assuming the user receives the message
                    'sender_role' => 'System',
                    'message_text' => $messageText,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Redirect to messagesPisces with success toast
                session()->flash('toast', [
                    'message' => 'Order placed successfully! Please check your messages to complete the GCash payment.',
                    'type' => 'success',
                ]);

                return redirect()->route('user.messagesPisces');
            }

            // Standard success message for other payment methods
            session()->flash('toast', [
                'message' => 'Order placed successfully! You can monitor your order in the Orders section.',
                'type' => 'success',
            ]);

            return redirect()->route('user.menu');
        } catch (\Exception $e) {
            // Log any errors for debugging
            Log::error('Order Store Error:', ['error' => $e->getMessage()]);

            // Return back with an error message
            return redirect()->back()->with('error', 'An error occurred while processing your order.');
        }
    }



    public function menuDetailsOrder($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the specific menu item by ID
        $menu = Menu::find($id);

        // Check if the menu item exists
        if (!$menu) {
            return redirect()->route('user.menu')->with('error', 'Menu item not found');
        }

        // Fetch the quantity from the request, default to 1
        $quantity = request()->input('quantity', 1);

        // Calculate the original total price
        $originalTotal = $menu->price * $quantity;

        // Apply a 5% discount if the user is eligible
        $hasDiscount = session('discount') && !$user->has_discount;
        $finalTotal = $hasDiscount ? $originalTotal * 0.95 : $originalTotal;

        // Pass the variables to the view
        $totalPrice = $finalTotal; // Ensure compatibility with the Blade template
        return view('user.menuDetailsOrder', compact('menu', 'user', 'quantity', 'originalTotal', 'finalTotal', 'hasDiscount', 'totalPrice'));
    }

    public function sendMessage(Request $request, $userId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string',
        ]);

        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }

        // Create the message
        $message = Message::create([
            // 'user_id' => $authUser->id, // Admin is the sender
            'user_id' => Auth::id(), // Admin is the sender
            'receiver_id' => '1', // User is the recipient
            'sender_role' => 'User',
            'message_text' => $validated['message_text'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'message_text' => $message->message_text,
                'created_at' => $message->created_at->diffForHumans(),
            ],
        ]);
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
