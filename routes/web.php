<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/* Admin-specific */
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminLogin;
use App\Http\Controllers\Admin\Auth\RegisteredUserController as AdminRegister;
use App\Http\Controllers\Admin\AdminController;

//  Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 🧑‍💻 Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    // Breeze Dashboard (Dashboard view uses resources/views/dashboard.blade.php)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile (default Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👤 Users CRUD + Project Assignment
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/assign-project', [UserController::class, 'assignProject'])->name('users.assign-project');
    Route::delete('/users/{user}/remove-project/{project}', [UserController::class, 'removeProject'])->name('users.remove-project');

    // 📁 Projects CRUD + User Assignment
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/assign-user', [ProjectController::class, 'assignUser'])->name('projects.assign-user');
    Route::delete('/projects/{project}/remove-user/{user}', [ProjectController::class, 'removeUser'])->name('projects.remove-user');

    // ✅ Tasks CRUD + Status Update
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Roles CRUD
Route::resource('roles', \App\Http\Controllers\RoleController::class);

});


//Admin Auth + Dashboard + Profile Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Admin Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLogin::class, 'create'])->name('login');
        Route::post('/login', [AdminLogin::class, 'store']);
        Route::get('/register', [AdminRegister::class, 'create'])->name('register');
        Route::post('/register', [AdminRegister::class, 'store']);
    });

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminController::class, 'destroy'])->name('profile.destroy');
        Route::post('/logout', [AdminLogin::class, 'destroy'])->name('logout');
    });
});

//  Using Middleware to Restrict Access in Routes or Controllers,   Added Today 14/05 
Route::group(['middleware' => ['role:Admin']], function () {
    Route::get('/admin-only-route', [SomeController::class, 'adminStuff']);
});

Route::group(['middleware' => ['role:User|Admin']], function () {
    Route::get('/shared-route', [SomeController::class, 'sharedStuff']);
});


//Breeze Auth Routes
require __DIR__.'/auth.php';







/*Today commented */
/*
// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// User routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', function () {
        return view('dashboard');
    })->name('user.dashboard');
});
*/

/*user Management System Routes */
/*
Route::get('/', function () {
    return redirect()->route('users.index');
});
*/

/*
Route::resource('users', UserController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

// Adding User Route when Clicking on Add User Button in users.index file
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');


// Additional routes for project assignments
Route::post('/users/{user}/assign-project', [UserController::class, 'assignProject'])
    ->name('users.assign-project');
Route::delete('/users/{user}/remove-project/{project}', [UserController::class, 'removeProject'])
    ->name('users.remove-project');

// Project user assignment routes
Route::post('/projects/{project}/assign-user', [ProjectController::class, 'assignUser'])
    ->name('projects.assign-user');
Route::delete('/projects/{project}/remove-user/{user}', [ProjectController::class, 'removeUser'])
    ->name('projects.remove-user');

// Task status update
Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])
    ->name('tasks.update-status');    

// Show Edit Form
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

// Handle Edit Submission
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
*/

/* require __DIR__.'/auth.php'; */