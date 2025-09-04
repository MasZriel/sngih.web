<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.notifications', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $unreadNotifications = $user->unreadNotifications()->take(5)->get();
                $notificationCount = $user->unreadNotifications()->count();
            } else {
                $unreadNotifications = collect();
                $notificationCount = 0;
            }
            
            $view->with([
                'unreadNotifications' => $unreadNotifications,
                'notificationCount' => $notificationCount,
            ]);
        });

        View::composer('layouts.app', function ($view) {
            $cart = session('cart', []);
            $cartItemCount = array_sum(array_column($cart, 'quantity'));
            $view->with('cartItemCount', $cartItemCount);
        });

        // Composer for Admin Layout
        View::composer('layouts.admin', function ($view) {
            if (Auth::check() && Auth::user()->isAdmin()) {
                $admin = Auth::user();
                $view->with('adminUnreadNotifications', $admin->unreadNotifications);
            } else {
                $view->with('adminUnreadNotifications', collect());
            }
        });
    }
}