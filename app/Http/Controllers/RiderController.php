<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rider;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riders = Rider::all();

        return view('admin.rider', compact('riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.riderCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        // Create a new menu entry in the database
        Rider::create([
            'name' => $validated['name'], // Store the category
        ]);

        return redirect()->route('admin.rider.index')->with('success', 'Rider added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rider = Rider::findOrFail($id);
        return view('admin.riderShow', compact('rider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider = Rider::findOrFail($id);

        return view('admin.riderEdit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rider = Rider::findOrFail($id);

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        // Update the category with new data
        $rider->update([
            'name' => $validated['name'], // Update category name
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.rider.index')->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rider = Rider::findOrFail($id);
        $rider->delete();
        return redirect()->route('admin.rider.index')->with('success', 'Menu item deleted successfully.');
    }
}
