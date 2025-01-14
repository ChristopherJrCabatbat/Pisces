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
use App\Models\Promotion;

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

    //  public function store(LoginRequest $request): RedirectResponse
    //  {
    //      $request->authenticate();

    //      $request->session()->regenerate();

    //      $user = $request->user();

    //      Log::info('User last login at: ' . $user->last_login_at);
    //      Log::info('Current time: ' . now());

    //      // Check if promotions have been shown in the current session
    //      if (!$request->session()->has('promotions_shown_during_session')) {
    //          $promotions = Promotion::all();
    //          $availablePromotions = [];

    //          foreach ($promotions as $promotion) {
    //              $lastShownKey = 'promotion_last_shown_' . $promotion->id;
    //              $lastShownDate = session($lastShownKey);

    //              // Check `how_often` to decide if the promotion should be displayed
    //              if (!$lastShownDate || Carbon::parse($lastShownDate)->lte(now()->subDays($promotion->how_often))) {
    //                  $availablePromotions[] = $promotion;

    //                  // Update the session with the last shown timestamp
    //                  session([$lastShownKey => now()]);
    //              }
    //          }

    //          // Save the promotions to session if available
    //          if (!empty($availablePromotions)) {
    //              session(['availablePromotions' => $availablePromotions]);
    //              session(['promotions_shown_during_session' => true]); // Mark as shown
    //          }
    //      }

    //      // Apply discount logic if user is inactive for 10+ days and has made an order
    //      if ($user->last_order) {
    //          Log::info('User last order at: ' . $user->last_order);
    //          Log::info('10 days ago: ' . now()->subDays(10));

    //          if (Carbon::parse($user->last_order)->lte(now()->subDays(10))) {
    //              $user->update(['has_discount' => true]);

    //              session()->flash('discount', 'Welcome back! Here’s a 5% discount on your next order.');
    //              Log::info('Discount applied for user: ' . $user->id);
    //          } else {
    //              Log::info('User not inactive for more than 10 days.');
    //          }
    //      } else {
    //          Log::info('User has no last order. Discount not applied.');
    //      }

    //      // Update last login timestamp
    //      $user->update(['last_login_at' => now()]);

    //      // Redirect user to the appropriate dashboard
    //      if ($user->role === 'Admin') {
    //          return redirect('admin/dashboard');
    //      } elseif ($user->role === 'Rider') {
    //          return redirect('rider/dashboard');
    //      } else {
    //          return redirect('user/dashboard');
    //      }
    //  }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        Log::info('User last login at: ' . $user->last_login_at);
        Log::info('Current time: ' . now());

        // Fetch all promotions and store them in the session
        $promotions = Promotion::all();

        if ($promotions->isNotEmpty()) {
            session(['availablePromotions' => $promotions]);
        }

        // Apply discount logic if user is inactive for 10+ days and has made an order
        if ($user->last_order) {
            Log::info('User last order at: ' . $user->last_order);
            Log::info('10 days ago: ' . now()->subDays(10));

            if (Carbon::parse($user->last_order)->lte(now()->subDays(10))) {
                $user->update(['has_discount' => true]);

                session()->flash('discount', 'Welcome back! Here’s a 5% discount on your next order.');
                Log::info('Discount applied for user: ' . $user->id);
            } else {
                Log::info('User not inactive for more than 10 days.');
            }
        } else {
            Log::info('User has no last order. Discount not applied.');
        }

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        // Redirect user to the appropriate dashboard
        if ($user->role === 'Admin') {
            return redirect('admin/dashboard');
        } elseif ($user->role === 'Rider') {
            return redirect('rider/dashboard');
        } else {
            return redirect('user/dashboard');
        }
    }



    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     $user = $request->user();

    //     Log::info('User last login at: ' . $user->last_login_at);
    //     Log::info('Current time: ' . now());

    //     // Check if the user is inactive for more than 10 days and has made at least one order
    //     if ($user->last_order) {
    //         Log::info('User last order at: ' . $user->last_order);
    //         Log::info('10 days ago: ' . now()->subDays(10));

    //         if (Carbon::parse($user->last_order)->lte(now()->subDays(10))) {
    //             $user->update(['has_discount' => true]);

    //             session()->flash('discount', 'Welcome back! Here’s a 5% discount on your next order.');
    //             Log::info('Discount applied for user: ' . $user->id);
    //         } else {
    //             Log::info('User not inactive for more than 10 days.');
    //         }
    //     } else {
    //         Log::info('User has no last order. Discount not applied.');
    //     }


    //     // Update the last login timestamp
    //     $user->update(['last_login_at' => now()]);

    //     // Redirect to appropriate dashboard
    //     if ($user->role === 'Admin') {
    //         return redirect('admin/dashboard');
    //     } elseif ($user->role === 'Rider') {
    //         return redirect('rider/dashboard');
    //     } else {
    //         return redirect('user/dashboard');
    //     }
    // }



    /**
     * Destroy an authenticated session.
     */

    public function destroy(Request $request): RedirectResponse
    {
        // Log out the user
        Auth::guard('web')->logout();

        // Clear all promotion-related session data
        $request->session()->forget('availablePromotions');
        foreach (Promotion::all() as $promotion) {
            $request->session()->forget('promotion_last_shown_' . $promotion->id);
        }

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
