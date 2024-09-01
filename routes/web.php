<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/dashboard', function () {
//     return view('/admin/dashboard');
//     // return view('login');
// });

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

//admin_user
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

//dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index']);
Route::post('/admin/dashboard/user', [DashboardController::class, 'user_search'])->name('dashboard.user_search');
Route::post('/admin/dashboard/user_count', [DashboardController::class, 'user_count'])->name('dashboard.user_count');
Route::get('/admin/dashboard/user_accept', [DashboardController::class, 'user_accept'])->name('dashboard.user_accept');
Route::get('/admin/dashboard/user_reject', [DashboardController::class, 'user_reject'])->name('dashboard.user_reject');
Route::post('/admin/dashboard/admin_list_search', [DashboardController::class, 'admin_list_search'])->name('admin_list.search');
Route::get('/admin/dashboard/list_fix', [DashboardController::class, 'list_fix'])->name('dashboard.list_fix');
Route::get('/admin/dashboard/list_accept', [DashboardController::class, 'list_accept'])->name('dashboard.list_accept');
Route::get('/admin/dashboard/list_reject', [DashboardController::class, 'list_reject'])->name('dashboard.list_reject');
Route::get('/admin/dashboard/close', [DashboardController::class, 'close'])->name('dashboard.close');



//user
Route::get('/user/home', [HomeController::class, 'index']);
Route::post('/user/home/search', [HomeController::class, 'search'])->name('home.search');
Route::post('/user/home/save', [HomeController::class, 'save'])->name('home.save');
Route::get('/user/list', [HomeController::class, 'list_index'])->name('list.index');
Route::post('/user/list/search', [HomeController::class, 'list_search'])->name('list.search');
Route::get('/user/edit/{request_no}', [HomeController::class, 'edit_index'])->name('edit.index');
Route::post('/user/edit/save', [HomeController::class, 'edit_save'])->name('edit.save');
Route::post('/user/edit/log', [HomeController::class, 'log_search'])->name('log.search');
Route::get('/user/check', [HomeController::class, 'check'])->name('edit.check');
Route::get('/user/cancel', [HomeController::class, 'cancel'])->name('edit.cancel');






Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->middleware('auth')->name('logout');
