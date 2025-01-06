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

    // public function index(Request $request)
    // {
    //     // Fetch search and filter parameters
    //     $search = $request->input('search', '');
    //     $filter = $request->input('filter', 'default'); // Default to "default"

    //     // Query riders with search and filter
    //     $riders = Rider::when($search, function ($query, $search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         })
    //         ->when($filter === 'alphabetically', function ($query) {
    //             $query->orderBy('name', 'asc'); // Alphabetically descending
    //         })
    //         ->when($filter === 'byRating', function ($query) {
    //             $query->orderBy('rating', 'desc'); // By rating descending
    //         })
    //         ->paginate(4) // Show 5 items per page
    //         ->appends(['search' => $search, 'filter' => $filter]); // Preserve query parameters in pagination links

    //     return view('admin.rider', compact('riders', 'filter', 'search'));
    // }

    public function index(Request $request)
    {
        // Fetch search and filter parameters
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'default'); // Default to "default"

        // Query riders with search and filter
        $riders = Rider::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
            ->when($filter === 'alphabetically', function ($query) {
                $query->orderBy('name', 'asc'); // Alphabetically descending
            })
            ->when($filter === 'byRating', function ($query) {
                $query->orderBy('rating', 'desc'); // By rating descending
            })
            ->get(); // Retrieve all results without pagination

        return view('admin.rider', compact('riders', 'filter', 'search'));
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
