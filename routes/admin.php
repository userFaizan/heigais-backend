<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
//
Route::prefix('admin')->middleware(['auth','role:admin|subadmin'])->group(function () {
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class,'index'])->middleware(['auth'])->name('admin.dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::post('admins/delete_admin',[App\Http\Controllers\Admin\UserAdminController::class,'delete_admin'])->name('delete_admin');
        Route::post('admins/block_admin',[App\Http\Controllers\Admin\UserAdminController::class,'block_admin'])->name('block_admin');
        Route::post('admins/activate_admin',[App\Http\Controllers\Admin\UserAdminController::class,'activate_admin'])->name('activate_admin');
        Route::get('admins/get_all_admins',[App\Http\Controllers\Admin\UserAdminController::class,'get_all_admins'])->name('get_all_admins');
        Route::resource('admins',App\Http\Controllers\Admin\UserAdminController::class);
    });

    Route::post('users/delete_user',[App\Http\Controllers\Admin\UserController::class,'delete_user'])->name('delete_user');
    Route::post('users/block_user',[App\Http\Controllers\Admin\UserController::class,'block_user'])->name('block_user');
    Route::post('users/activate_user',[App\Http\Controllers\Admin\UserController::class,'activate_user'])->name('activate_user');
    Route::get('users/get_all_users',[App\Http\Controllers\Admin\UserController::class,'get_all_users'])->name('get_all_users');
    Route::resource('users',App\Http\Controllers\Admin\UserController::class);

    Route::post('events/delete_event',[App\Http\Controllers\Admin\EventController::class,'delete_event'])->middleware(['auth'])->name('delete_event');
    Route::post('events/get_child_categories',[App\Http\Controllers\Admin\EventController::class,'get_child_categories'])->name('get_child_categories');
    Route::get('events/get_all_events',[App\Http\Controllers\Admin\EventController::class,'get_all_events'])->name('get_all_events');
    Route::resource('events',App\Http\Controllers\Admin\EventController::class);

    Route::post('categories/delete_category',[ArticleController::class,'delete_category'])->middleware(['auth'])->name('delete_category');
    Route::get('categories/get_all_categories',[App\Http\Controllers\Admin\CategoryController::class,'get_all_categories'])->name('get_all_categories');
    Route::resource('categories',App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('notifications',App\Http\Controllers\Admin\NotificationController::class);

    Route::post('admin/interests/calculate',[App\Http\Controllers\Admin\InterestController::class,'calculate'])->name('interests.calculate');
    Route::post('admin/interests/users',[App\Http\Controllers\Admin\InterestController::class,'get_interested_users'])->name('intrested.users');


});

require __DIR__.'/auth.php';
