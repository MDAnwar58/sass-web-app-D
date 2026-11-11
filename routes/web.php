<?php

use App\Http\Controllers\Core\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::resource('organizations', OrganizationController::class)->except(['create', 'store']);
    Route::post('/organizations/{org}/switch', [OrganizationController::class, 'switch'])
         ->name('organizations.switch');
});

require __DIR__.'/settings.php';
require __DIR__.'/core.php';
