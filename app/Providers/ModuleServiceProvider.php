<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'verified', 'module:inventory'])
            ->prefix('inventory')
            ->name('inventory.')
            ->group(base_path('routes/inventory.php'));

        Route::middleware(['web', 'auth', 'verified', 'module:lms'])
            ->prefix('lms')
            ->name('lms.')
            ->group(base_path('routes/lms.php'));
    }
}
