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

        try {
            // Create a new rider entry in the database
            Rider::create([
                'name' => $validated['name'],
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Rider added successfully.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to add rider. Please try again.',
                'type' => 'error',
            ]);
        }

        return redirect()->route('admin.rider.index');
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

        try {
            // Update the rider with new data
            $rider->update([
                'name' => $validated['name'],
            ]);

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Rider updated successfully.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to update rider. Please try again.',
                'type' => 'error',
            ]);
        }

        return redirect()->route('admin.rider.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rider = Rider::findOrFail($id);

        try {
            // Delete the rider
            $rider->delete();

            // Set success toast message
            session()->flash('toast', [
                'message' => 'Rider deleted successfully.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Set error toast message
            session()->flash('toast', [
                'message' => 'Failed to delete rider. Please try again.',
                'type' => 'error',
            ]);
        }

        return redirect()->route('admin.rider.index');
    }
}
