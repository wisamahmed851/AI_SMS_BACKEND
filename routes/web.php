<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FeeController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Super Admin Routes
    Route::prefix('super-admin')->middleware('role:super_admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        // Super Admin acts mostly as Admin, can share Admin routes or explicitly define identical resources
        // For simplicity, we are redirecting super admins to admin views, or we can use admin routes for them natively.
    });

    // Admin Routes
    Route::prefix('admin')->middleware('role:admin,super_admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Resources
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::resource('parents', ParentController::class)->except(['show']);
        Route::resource('fees', FeeController::class);
        Route::post('fees/{fee}/payments', [FeeController::class, 'addPayment'])->name('fees.payments.store');
        
        Route::resource('teacher-attendances', \App\Http\Controllers\TeacherAttendanceController::class)->only(['index', 'store']);
        
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class)->only(['index', 'update']);
    });

    // Teacher Routes
    Route::prefix('teacher')->middleware('role:teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/attendance', [TeacherController::class, 'attendance'])->name('attendance');
        Route::post('/attendance', [TeacherController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/results', [TeacherController::class, 'results'])->name('results');
        Route::post('/results', [TeacherController::class, 'storeResults'])->name('results.store');
        
        Route::resource('tasks', \App\Http\Controllers\TaskController::class);
        Route::get('tasks/{task}/submissions', [\App\Http\Controllers\TaskController::class, 'show'])->name('tasks.submissions');
        
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class)->only(['index', 'create', 'store']);
    });

    // Student Routes
    Route::prefix('student')->middleware('role:student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        Route::resource('tasks', \App\Http\Controllers\TaskController::class)->only(['index', 'show']);
        Route::post('tasks/{task}/submit', [\App\Http\Controllers\TaskController::class, 'submit'])->name('tasks.submit');
        
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class)->only(['index', 'create', 'store']);
    });

    // Parent Routes
    Route::prefix('parent')->middleware('role:parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
        
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class)->only(['index', 'create', 'store']);
    });
});
