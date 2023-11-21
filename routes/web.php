<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

// Home
Route::redirect('/', '/login');

// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'list')->name('posts');
    Route::get('/posts/{id}', 'show');
});


// API
Route::controller(PostController::class)->group(function () {
    Route::put('/api/posts', 'create');
    Route::delete('/api/posts/{post_id}', 'delete');
});

Route::controller(CommentController::class)->group(function () {
    Route::put('/api/posts/{post_id}', 'create');
    Route::post('/api/comment/{id}', 'update');
    Route::delete('/api/comment/{id}', 'delete');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


Route::middleware(['admin'])->controller(AdminController::class)->group(function () {
    // Views
    Route::get('/admin/dashboard', 'showAdminDashboard')->name('admin.dashboard');
    Route::get('/admin/users', 'listUsers')->name('admin.listUsers');

    // API  - USERS
    Route::post('/admin/users/register','createUser')->name('admin.createUser');
    Route::post('/admin/users/{user}/toggle', 'toggleAdminStatus')->name('admin.toggleAdmin');
    Route::post('/admin/users/block/{id}', 'blockUser')->name('admin.blockUser');
    Route::post('/admin/users/unblock/{id}', 'unblockUser')->name('admin.unblockUser');


    Route::post('/admin/login', 'adminLogin');
});




