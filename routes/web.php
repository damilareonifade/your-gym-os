<?php

use App\Http\Controllers\Tenant\TenantLoginUsersController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/test-route', function () {
    return 'Route works!';
});

Route::post('/login-users', [TenantLoginUsersController::class, 'store'])
    ->name('tenant.login-users.store');
