<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Models\ClassroomUser;
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

    // Admin Panel
    Route::middleware(['role:admin'])->group(function () {
        // Manage Users
        // Permite enviar los usuarios eliminados como parametro en las URL
        Route::bind('user', function($id) {
            return User::withTrashed()->findOrFail($id);
        });
        Route::get('/users/students', [UserController::class, 'students'])->name('users.students');
        Route::put('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('users', UserController::class);

        // Sala de Clases
        //Route::post('/classroom/{classroom}/add_student', [ClassroomController::class, 'addStudent'])->name('classrooms.addStudent');
        Route::resource('classrooms', ClassroomController::class);

        // Suscripciones
        Route::resource('classrooms.subscriptions', SubscriptionController::class)->shallow();

        // Estudiantes
        // Permite enviar los estudiantes eliminados como parametro en las URL
        Route::bind('classroomUser', function($id) {
            return ClassroomUser::withTrashed()->findOrFail($id);
        });
        Route::get('/classroomUsers', [ClassroomUserController::class, 'index'])->name('classroomUsers.index');
        Route::get('/classroomUsers/{classroomUser}/payments', [ClassroomUserController::class, 'payments'])->name('classroomUsers.payments');
        Route::get('/classroomUsers/{classroomUser}/subscription', [ClassroomUserController::class, 'subscription'])->name('classroomUsers.subscription');
        Route::post('/classroomUsers/store', [ClassroomUserController::class, 'store'])->name('classroomUsers.store');
        Route::delete('/classroomUsers/{classroomUser}/destroy', [ClassroomUserController::class, 'destroy'])->name('classroomUsers.destroy');
        Route::put('/classroomUsers/{classroomUser}/restore', [ClassroomUserController::class, 'restore'])->name('classroomUsers.restore');

        // Payments
        Route::get('/payments/create/{classroomUser?}', [PaymentController::class, 'create'])->name('payments.create');
        Route::resource('payments', PaymentController::class)->except(['create']);
    });
});
