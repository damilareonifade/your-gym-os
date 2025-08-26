<?php

namespace App\Providers;

use App\Models\User;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('viewPulse', function (User $user) {
            return in_array($user->email, [
                "admin@gymno.com",
            ]);
        });

        /**
         * Configure default table settings for Filament tables
         */
        Table::configureUsing(function (Table $table): void {
            $table
                ->filtersLayout(FiltersLayout::Dropdown)
                ->paginationPageOptions([100, 200, 300, 400]);
        });
    }
}
