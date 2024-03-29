<?php

use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::resource('users', UserController::class)->except(['show']);

        // Classrooms
        Route::resource('classrooms', ClassroomController::class)->except(['show']);
        Route::get('/classrooms/active-students', [ClassroomController::class, 'activeStudents'])->name('classrooms.active_students');
        Route::put('/classrooms/{classroom}/restore', [ClassroomController::class, 'restore'])->name('classrooms.restore');
        Route::get('/classrooms/{classroom}/students', [ClassroomController::class, 'students'])->name('classrooms.students');
        Route::get('/classrooms/{classroom}/lessons', [ClassroomController::class, 'lessons'])->name('classrooms.lessons');
        Route::post('/classrooms/students/add', [ClassroomController::class, 'addStudent'])->name('classrooms.students.add');
        Route::post('/classrooms/students/remove', [ClassroomController::class, 'removeStudent'])->name('classrooms.students.remove');
        Route::post('/classrooms/students/restore', [ClassroomController::class, 'restoreStudent'])->name('classrooms.students.restore');

        // Subscriptions
        Route::resource('classrooms.subscriptions', SubscriptionController::class)->shallow()->only(['store', 'destroy']);

        // Students
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::put('/students/{classroomUser}', [StudentController::class, 'update'])->name('students.update');
        Route::get('/students/{classroomUser}/payments', [StudentController::class, 'payments'])->name('students.payments');
        Route::get('/students/{classroomUser}/subscription', [StudentController::class, 'subscription'])->name('students.subscription');

        // Payments
        Route::get('/payments/create/{classroomUser?}', [PaymentController::class, 'create'])->name('payments.create');
        Route::resource('payments', PaymentController::class)->except(['create', 'show']);
        Route::get('/payments/{payment}/students', [PaymentController::class, 'students'])->name('payments.students');

        // Lessons
        Route::get('/lessons/create/{classroom?}', [LessonController::class, 'create'])->name('lessons.create');
        Route::resource('lessons', LessonController::class)->except(['create', 'show']);
    });
});
