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
                'admin.test-email',
                'admin.dashboard',

                'admin.userUpdate',
                'admin.userDestroy',
                'admin.saveFeedback',

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
               
                'admin.category.index',
                'admin.category.create',
                'admin.category.store',
                'admin.category.show',
                'admin.category.edit',
                'admin.category.update',
                'admin.category.destroy',
               
                'admin.rider.index',
                'admin.rider.create',
                'admin.rider.store',
                'admin.rider.show',
                'admin.rider.edit',
                'admin.rider.update',
                'admin.rider.destroy',

                'admin.deliveryDetails',
                'admin.updateStatus',
                'admin.assignRider',
                'admin.deliveryCreateRider',
                'admin.storeRider',
                   
                'admin.promotions.index',
                'admin.promotions.create',
                'admin.promotions.store',
                'admin.promotions.show',
                'admin.promotions.edit',
                'admin.promotions.update',
                'admin.promotions.destroy',

                'admin.toggleAvailability',

                'admin.customers',
                'admin.monitoring',
                'admin.updates',
                'admin.viewOrders',
                'admin.getOrderDetails',

                'admin.feedback',
                'admin.updateSentiment',
                'admin.respondFeedback',

                'admin.customerMessages',
                'admin.messageUser',
                'admin.sendMessage',
                'admin.markAsRead',
            ],

            'Rider' => [
                'rider.dashboard',
            ],

            'User'  => [
                'user.dashboard',

                'user.userUpdate',
                'user.submitExperience',

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
                'user.orderRepeat',
                'user.orderStore',
                'user.reviewOrderStore',
                
                'user.feedback.index',
                'user.feedback.create',
                'user.feedback.store',
                'user.feedback.show',
                'user.feedback.edit',
                'user.feedback.update',
                'user.feedback.destroy',

                'user.messages',
                'user.messagesPisces',
                'user.sendMessage',
                'user.markAsRead',
                
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
