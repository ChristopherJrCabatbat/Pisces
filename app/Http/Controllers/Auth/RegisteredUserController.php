<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // return view('auth.register');
        return view('start.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         // 'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         // 'name' => $request->name,
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'contact_number' => $request->contact_number,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     // return redirect(route('dashboard', absolute: false));
    //     if ($request->user()->role === 'Admin') {
    //         return redirect('admin/dashboard');
    //     } elseif ($request->user()->role === 'Staff') {
    //         return redirect('staff/dashboard');
    //     } else {
    //         return redirect('user/dashboard');
    //     }
    // }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Set the newsletter_subscription to true if the checkbox is checked
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'newsletter_subscription' => $request->has('newsletter_subscription'), // Sets to true if checkbox is checked
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user role
        if ($request->user()->role === 'Admin') {
            return redirect('admin/dashboard');
        } elseif ($request->user()->role === 'Staff') {
            return redirect('staff/dashboard');
        } else {
            return redirect('user/dashboard');
        }
    }
}
