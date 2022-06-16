<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Manage Users
    Route::middleware(['role:admin'])->group(function () {
        // Permite enviar los usuarios eliminados como parametro en las URL
        Route::bind('user', function($id) {
            return User::withTrashed()->findOrFail($id);
        });
        Route::resource('users', UserController::class);
        Route::put('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    });
});
