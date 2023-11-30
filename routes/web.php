<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;


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
    Route::get('/posts', 'listPosts')->name('posts');
    Route::post('/posts', 'create')->name('post.create');
    Route::delete('/posts/{id}', 'delete')->name('post.delete');
    Route::get('/posts/{id}', 'show');
    Route::get('/posts/{id}', 'open')->name('post.open');
    Route::get('/posts/{id}/edit', 'edit')->name('post.edit');
});


// API - POSTS
Route::controller(PostController::class)->group(function () {
    Route::put('/api/posts', 'create');
    Route::delete('/api/posts/{post_id}', 'delete');
});

// Comments
Route::controller(CommentController::class)->group(function () {
    Route::get('/posts/{post_id}/comments', 'list');
    Route::get('/posts/{post_id}/comments/{id}', 'show');
    Route::post('/posts/{post_id}/comments', 'create')->name('comment.create');
});

// API - COMMENTS
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

// Registration
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profile
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile/{username}', 'show')->name('profile.show');
    Route::get('/profile/{username}/edit', 'edit')->name('profile.edit');
    Route::put('/profile/update/{username}', 'update')->name('profile.update');

    Route::get('/profile/{username}/posts', 'listUserPosts')->name('profile.listPosts');
    Route::get('/profile/{username}/comments', 'listComments')->name('profile.listComments');
});

// API - PROFILE
Route::controller(ProfileController::class)->group(function () {
    Route::post('/api/profile/{username}/update', 'update');
    Route::post('/api/profile/{username}/delete', 'delete');
});


// Admin
Route::middleware(['admin'])->controller(AdminController::class)->group(function () {
    // Views
    Route::get('/admin/dashboard', 'showAdminDashboard')->name('admin.dashboard');
    Route::get('/admin/users', 'listUsers')->name('admin.listUsers');

    // API  - USERS
    Route::post('/admin/users/register','createUser')->name('admin.createUser');
    Route::post('/admin/users/{user}/toggle', 'toggleAdminStatus')->name('admin.toggleAdmin');
    Route::post('/admin/users/block', 'blockUser')->name('admin.blockUser');
    Route::post('/admin/users/unblock/{id}', 'unblockUser')->name('admin.unblockUser');


    Route::post('/admin/login', 'adminLogin');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'global_search')->name('search.results');
});

