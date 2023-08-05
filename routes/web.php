<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\ChildRewardController;
use App\Http\Controllers\ChildRewardImageController;
use App\Http\Controllers\ChildTaskImageController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParentTaskController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RewardImageController;
use App\Http\Controllers\TaskController;
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

Route::prefix('child')
    ->middleware(['auth', 'can:is_child'])
    ->name('child.')
    ->group(function(){
        Route::get('/parent', [ParentController::class, 'index'])->name('parent.index');
        Route::get('/parent/create', [ParentController::class, 'create'])->name('parent.create');
        Route::post('/parent/store', [ParentController::class, 'store'])->name('parent.store');
        Route::resource('/tasks', TaskController::class)->only(['show', 'index', 'edit', 'update']);
        Route::resource('/rewards', ChildRewardController::class);
        Route::resource('reward.image', ChildRewardImageController::class)
        ->only(['create', 'store', 'destroy']);
        Route::resource('task.image', ChildTaskImageController::class)
        ->only(['create', 'store', 'destroy']);
    });



