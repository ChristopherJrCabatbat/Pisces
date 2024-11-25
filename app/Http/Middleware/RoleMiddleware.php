<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $role = $user->role;

        // Define the allowed route names for each role
        $allowedRoutes = [
            'Admin' => [
                'admin.dashboard',

                // Allow all resource routes for 'menu'
                'admin.menu.index',
                'admin.menu.create',
                'admin.menu.store',
                'admin.menu.show',
                'admin.menu.edit',
                'admin.menu.update',
                'admin.menu.destroy',

                'admin.menuCreateCategory',
                'admin.menuEditCategory',
                'admin.storeCategory',

                'admin.delivery.index',
                'admin.delivery.create',
                'admin.delivery.store',
                'admin.delivery.show',
                'admin.delivery.edit',
                'admin.delivery.update',
                'admin.delivery.destroy',

                'admin.deliveryDetails',
                'admin.updateStatus',
                'admin.deliveryUpdate',

                'admin.customers',
                'admin.feedback',
                'admin.updates',
                'admin.monitoring',
            ],

            'Staff' => [
                'staff.patientRecord',
            ],

            'User'  => [
                'user.dashboard',

                'user.menu',
                
                'user.menuView',
                
                'user.addToFavorites',
                'user.favorites',
                
                'user.menuDetail',
                'user.addToCart',
                'user.addToCartModal',
                'user.addToFavorites',
                'user.shoppingCart',
                'user.removeCart',
                'user.updateQuantity',

                'user.order',
                'user.orderView',
                'user.menuDetails',
                'user.menuDetailsOrder',

                'user.delivery.index',
                'user.delivery.create',
                'user.delivery.store',
                'user.delivery.show',
                'user.delivery.edit',
                'user.delivery.update',
                'user.delivery.destroy',

                'user.orders',
                'user.orderStore',

                'user.messages',
                'user.messagesPisces',
                'user.shopUpdates',
                'user.trackOrder',
                'user.reviewOrder',
            ],
        ];

        // Check if the current route name is in the allowed routes for the user's role
        if (!in_array($request->route()->getName(), $allowedRoutes[$role] ?? [])) {
            $message = "You can only access {$role}'s files. Please log out first to continue.";
            session()->flash('error', $message);

            // Redirect to the first allowed route (dashboard)
            return back();
        }

        return $next($request);
    }
}
