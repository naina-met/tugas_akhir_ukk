<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Peminjaman;

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
        Gate::define('manage-users', function ($user) {
            return $user->role === 'Superadmin';
        });

        Gate::define('manage-stock-out', function ($user) {
            return $user->role !== 'Superadmin';
        });

        View::composer('*', function ($view) {
        if (auth()->check() && auth()->user()->role !== 'user') {
            $view->with('pendingLoans', Peminjaman::where('status', 'pending')
                ->with(['user', 'item'])
                ->latest()
                ->get());
        }
    });
    }
}
