<?php

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PageController;
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

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('admin')->group(function () {
        // Route::get('dashboard', [DashboardController::class, 'dashboard']);

        Route::get('users/logout', [UserController::class, 'logout'])->name('users.logout');

        Route::resource('users', UserController::class);
        Route::post('users/update/{id}', [UserController::class, 'update']);
        Route::resource('user-roles', UserRoleController::class);
        Route::post('user-roles/update/{id}', [UserRoleController::class, 'update']);

        Route::resource('news', PostController::class);
        Route::post('news/update/{id}', [PostController::class, 'update']);

        Route::resource('sliders', SliderController::class);
        Route::post('sliders/update/{id}', [SliderController::class, 'update']);

        Route::resource('settings', SettingsController::class);
        Route::post('settings/update/{id}', [SettingsController::class, 'update']);

        
        Route::get('page-content/{page}', [PageContentController::class, 'page']);
        Route::post('page-content/{page}', [PageContentController::class, 'updateContent']);
        
        Route::resource('messages', MessageController::class);

        
    });

});


Route::get('/', [PageController::class, 'index']);
Route::get('news', [PageController::class, 'news']);
Route::get('news/{slug}', [PageController::class, 'newsInfo']);
Route::get('contact-us', [PageController::class, 'contact']);
Route::post('contact-us', [PageController::class, 'sendMessage']);
Route::get('about-us', [PageController::class, 'about']);
Route::get('privacy-policy', [PageController::class, 'privacyPolicy']);
Route::get('terms-of-service', [PageController::class, 'termsOfService']);

Route::get('login', [UserController::class, 'login'])->name('login');

Route::post('login', [UserController::class, 'doLogin'])->name('user.doLogin');
