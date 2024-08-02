<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
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

Route::get('/', function () {
    // return view('welcome');
    return view('login');
});

Route::get('/dashboard', function () {
    return view('/admin/dashboard');
    // return view('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

//user
Route::get('/admin/user', [UserController::class, 'index']);
Route::post('/admin/user/search', [UserController::class, 'search'])->name('user.search');
Route::get('/admin/user/edit', [UserController::class, 'edit'])->name('user.edit');
Route::post('/admin/user/save', [UserController::class, 'save'])->name('user.save');
Route::post('/admin/user/delete', [UserController::class, 'delete'])->name('user.delete');
Route::post('/admin/user/add', [UserController::class, 'add'])->name('user.add');

Route::get('/admin/item', [ItemController::class, 'index']);
Route::post('/admin/item/search', [ItemController::class, 'search'])->name('item.search');
Route::get('/admin/item/edit', [ItemController::class, 'edit'])->name('item.edit');
Route::post('/admin/item/save', [ItemController::class, 'save'])->name('item.save');
Route::post('/admin/item/delete', [ItemController::class, 'delete'])->name('item.delete');
Route::post('/admin/item/add', [ItemController::class, 'addItem'])->name('item.add');
