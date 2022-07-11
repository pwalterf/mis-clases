<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomUserController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Models\Classroom;
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
        
        // Permite enviar los usuarios eliminados como parametro en las URL
        Route::bind('user', function($id) {
            return User::withTrashed()->findOrFail($id);
        });
        // Permite enviar los estudiantes eliminados como parametro en las URL
        Route::bind('classroom', function($id) {
            return Classroom::withTrashed()->findOrFail($id);
        });
        // Permite enviar los estudiantes eliminados como parametro en las URL
        Route::bind('classroomUser', function($id) {
            return ClassroomUser::withTrashed()->findOrFail($id);
        });

        // Manage Users
        Route::get('/users/students', [UserController::class, 'students'])->name('users.students');
        Route::put('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('users', UserController::class);

        // Sala de Clases
        Route::resource('classrooms', ClassroomController::class);
        Route::put('/classrooms/{classroom}/restore', [ClassroomController::class, 'restore'])->name('classrooms.restore');
        Route::get('/classrooms/{classroom}/students', [ClassroomController::class, 'students'])->name('classrooms.students');
        Route::get('/classrooms/{classroom}/lessons', [ClassroomController::class, 'lessons'])->name('classrooms.lessons');

        // Suscripciones
        Route::resource('classrooms.subscriptions', SubscriptionController::class)->shallow();

        // Estudiantes
        Route::get('/classroomUsers', [ClassroomUserController::class, 'index'])->name('classroomUsers.index');
        Route::get('/classroomUsers/{classroomUser}/payments', [ClassroomUserController::class, 'payments'])->name('classroomUsers.payments');
        Route::get('/classroomUsers/{classroomUser}/subscription', [ClassroomUserController::class, 'subscription'])->name('classroomUsers.subscription');
        Route::post('/classroomUsers/store', [ClassroomUserController::class, 'store'])->name('classroomUsers.store');
        Route::delete('/classroomUsers/{classroomUser}/destroy', [ClassroomUserController::class, 'destroy'])->name('classroomUsers.destroy');
        Route::put('/classroomUsers/{classroomUser}/restore', [ClassroomUserController::class, 'restore'])->name('classroomUsers.restore');

        // Payments
        Route::get('/payments/create/{classroomUser?}', [PaymentController::class, 'create'])->name('payments.create');
        Route::resource('payments', PaymentController::class)->except(['create']);
        Route::get('/payments/{payment}/students', [PaymentController::class, 'students'])->name('payments.students');

        // Lessons
        Route::get('/lessons/create/{classroom?}', [LessonController::class, 'create'])->name('lessons.create');
        Route::get('/lessons/classrooms', [LessonController::class, 'classrooms'])->name('lessons.classrooms');
        Route::resource('lessons', LessonController::class)->except(['create']);
    });
});
