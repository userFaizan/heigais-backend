<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//open routes
Route::post('/login',[App\Http\Controllers\Api\AuthController::class,'login']);
Route::post('/social_auth',[App\Http\Controllers\Api\AuthController::class,'social_auth']);
Route::post('/register',[App\Http\Controllers\Api\AuthController::class,'register']);
Route::post('/forget_password',[\App\Http\Controllers\Api\AuthController::class,'send_mail']);
Route::post('/match_otp',[\App\Http\Controllers\Api\AuthController::class,'match_otp']);
Route::get('/langs',[App\Http\Controllers\Api\LanguageController::class,'index']);
Route::get('/categories',[App\Http\Controllers\Api\CategoryController::class,'index']);
Route::get('/categories/{id}',[App\Http\Controllers\Api\CategoryController::class,'getById']);
Route::get('/events',[App\Http\Controllers\Api\EventController::class,'get_events']);
Route::get('/search',[App\Http\Controllers\Api\EventController::class,'search_events']);
Route::get('/events/category/{id}',[App\Http\Controllers\Api\EventController::class,'get_events_cats']);
Route::get('/event/{id}',[App\Http\Controllers\Api\EventController::class,'get_event']);
Route::get('/get-parent-categories',[App\Http\Controllers\Api\CategoryController::class,'parent_categories']);
Route::get('/get-parent-categories',[App\Http\Controllers\Api\CategoryController::class,'parent_categories']);
Route::get('/get-event-types',[App\Http\Controllers\Api\EventTypeController::class,'event_types']);
Route::post('/feedback',[App\Http\Controllers\Api\FeedbackController::class,'feedback']);
Route::post('get-app-text/{id}',[App\Http\Controllers\Api\AppTextController::class,'get_app_text']);
Route::delete('/user/delete/{id}',[App\Http\Controllers\Api\AuthController::class, 'delete']);

// authentication locked routes
Route::middleware(['auth:api'])->group(function () {
    Route::put("/reset_password",[\App\Http\Controllers\Api\AuthController::class,'reset_password']);
    Route::put("/change_password",[\App\Http\Controllers\Api\AuthController::class,'update_password']);
    Route::get('/auth/user',[App\Http\Controllers\Api\AuthController::class,'get_user']);
    Route::post('/update/user',[App\Http\Controllers\Api\AuthController::class,'update_user']);
    Route::get('/my-events',[App\Http\Controllers\Api\EventController::class,'my_events']);
    Route::post('/add-event',[App\Http\Controllers\Api\EventController::class,'add_event']);
    Route::post('/update-event/{id}',[App\Http\Controllers\Api\EventController::class,'update_event']);
    Route::delete('/delete-event/{id}',[App\Http\Controllers\Api\EventController::class,'delete_event']);
    Route::post('/add-notification-request',[App\Http\Controllers\Api\NotificationRequestController::class,'add_notification_request']);
    Route::post('/update-notification-request/{id}',[App\Http\Controllers\Api\NotificationRequestController::class,'update_notification_request']);
    Route::get('/show-notification-requests',[App\Http\Controllers\Api\NotificationRequestController::class,'show_notification_requests']);
    Route::get('/delete-notification-request/{id}',[App\Http\Controllers\Api\NotificationRequestController::class,'delete_notification_request']);
    Route::post('/app-settings',[App\Http\Controllers\Api\SettingController::class,'update_setting']);

    Route::post('/get-client-token',[App\Http\Controllers\Api\PaymentController::class,'get_client_token']);
    Route::post('/checkout',[App\Http\Controllers\Api\PaymentController::class,'checkout']);
    Route::post('/pay',[App\Http\Controllers\Api\PaymentController::class,'pay']);
    Route::get('/show-transections',[App\Http\Controllers\Api\WaletController::class,'my_transections']);
    Route::get('my-walet',[App\Http\Controllers\Api\WaletController::class,'my_walet']);
    Route::get('cat',[App\Http\Controllers\Api\AppTextController::class,'cat']);

    Route::post('logout',[App\Http\Controllers\Api\AuthController::class,'logout']);

});
