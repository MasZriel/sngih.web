<?php

namespace App\Providers;

use App\Models\Wishlist;
use App\Policies\WishlistPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Wishlist::class, WishlistPolicy::class);

        View::composer('layouts.app', function ($view) {
            $cart = session()->get('cart', []);
            $cartItemCount = 0;
            foreach ($cart as $id => $details) {
                $cartItemCount += $details['quantity'];
            }
            $view->with('cartItemCount', $cartItemCount);
        });

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

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
