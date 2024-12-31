<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Carbon\Carbon;

use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // return view('auth.login');
        return view('start.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     if ($request->user()->role === 'Admin') {
    //         return redirect('admin/dashboard');
    //     } elseif ($request->user()->role === 'Rider') {
    //         return redirect('rider/dashboard');
    //     } else {
    //         return redirect('user/dashboard');
    //     }
    // }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     $user = $request->user();

    //     // Update the last login timestamp
    //     $user->update(['last_login_at' => now()]);

    //     // Check for inactivity and add a discount if applicable
    //     if ($user->checkInactivityDiscount()) {
    //         session()->flash('discount', 'Welcome back! Here’s a 5% discount on your next order.');
    //     }

    //     if ($user->role === 'Admin') {
    //         return redirect('admin/dashboard');
    //     } elseif ($user->role === 'Rider') {
    //         return redirect('rider/dashboard');
    //     } else {
    //         return redirect('user/dashboard');
    //     }
    // }


    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $user = $request->user();
    
        Log::info('User last login at: ' . $user->last_login_at);
        Log::info('Current time: ' . now());
    
        // Check if the user is inactive for more than 5 days
        if ($user->last_login_at && Carbon::parse($user->last_login_at)->lte(now()->subDays(5))) {
            session()->flash('discount', 'Welcome back! Here’s a 5% discount on your next order.');
            Log::info('Discount flash message set for user: ' . $user->id);
        } else {
            Log::info('No discount for user: ' . $user->id);
        }
    
        // Update the last login timestamp
        $user->update(['last_login_at' => now()]);
    
        // Redirect to appropriate dashboard
        if ($user->role === 'Admin') {
            return redirect('admin/dashboard');
        } elseif ($user->role === 'Rider') {
            return redirect('rider/dashboard');
        } else {
            return redirect('user/dashboard');
        }
    }



    /**
     * Destroy an authenticated session.
     */

    public function destroy(Request $request): RedirectResponse
    {
        // Log out the user
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
