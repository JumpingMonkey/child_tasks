<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ParentTaskController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RewardImageController;
use App\Http\Controllers\UserAccountController;
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

Route::get('/', [IndexController::class, 'index'])
    ->middleware('auth')
    ->name('index');

// Registration and Authentification
Route::controller(AuthController::class)->group(function(){
    Route::get('login', 'create')->name('login');
    Route::post('login', 'store')->name('login.store');
    Route::delete('logout', 'destroy')->name('logout');
});

Route::resource('user-account', UserAccountController::class)
    ->only(['create', 'store']);


Route::prefix('parent')
    ->middleware(['auth', 'can:is_parent'])
    ->name('parent.')
    ->group(function(){
        Route::controller(ChildrenController::class)->group(function(){
            Route::get('/children', 'index')->name('children.get');
            Route::delete('/children/{child}', 'detach')->name('children.detach');
            Route::get('/children/attach', 'generateAttachCode')->name('children.attach');
        });
        
        Route::resource('/tasks', ParentTaskController::class);
        
        Route::resource('/rewards', RewardController::class);
        
        Route::resource('reward.image', RewardImageController::class)
        ->only(['create', 'store', 'destroy']);
    });




