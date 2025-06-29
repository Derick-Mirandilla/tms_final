<?php

use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome page route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route - accessible only to authenticated and verified users
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Routes accessible only to authenticated users (middleware 'auth')
Route::middleware('auth')->group(function () {
    // User Profile Management Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Management Routes - Accessible by Super Admin, Manager, Agent Low, Agent Medium
    Route::resource('customers', CustomerController::class);

    // Ticket Management Routes - Accessible by all authenticated roles, with internal policies
    Route::resource('tickets', TicketController::class);
    // Specific routes for ticket actions
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assignTicket'])->name('tickets.assign');
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('tickets.addComment');
    Route::post('tickets/{ticket}/update-actions', [\App\Http\Controllers\TicketController::class, 'updateActions'])->name('tickets.updateActions');
    Route::post('tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::post('tickets/{ticket}/update-priority', [TicketController::class, 'updatePriority'])->name('tickets.updatePriority');
});

// Admin Panel Routes - Accessible only by users with the 'super_admin' role
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management (CRUD for users)
    Route::resource('users', UserController::class)->except(['show']); // No 'show' view needed for users
    // Custom route to assign roles to users
    Route::put('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');


    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
});


// Laravel Breeze Authentication Routes (includes login, register, password reset, etc.)
// Make sure this is included at the end or in a place that doesn't conflict
// with your custom route definitions. It typically defines the default auth routes.
require __DIR__.'/auth.php';

