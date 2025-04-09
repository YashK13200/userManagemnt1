<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

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
