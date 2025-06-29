<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Models\User; // Ensure User model is imported for full_name accessor
use App\Models\Customer; // Ensure Customer model is imported for full_name accessor


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
        // Register the TicketObserver
        Ticket::observe(TicketObserver::class);
    }
}