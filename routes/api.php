<?php

use App\Http\Controllers\Api\ParentSide\CustomRegularTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentSide\ChildController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ParentSide\RegularTaskController;
use App\Http\Controllers\Api\ParentSide\RewardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('adult')
    ->name('adult.')
    
    // ->middleware('can:is_adult_class')
    ->group(function(){
        Route::apiResource('children', ChildController::class);
        Route::get('get_child_token/{child}', [ChildController::class, 'getAccessTokenByChild']);
        Route::controller(RegularTaskController::class)
        ->prefix('regular_tasks')
        ->group(function(){
            Route::get('/{child}', 'getRegularTaskTemplatesByChild');
            Route::put('/{child}', 'updateRegularTaskTemplates');
            Route::controller(CustomRegularTaskController::class)
            ->prefix('custom_tasks')
            ->group(function(){
                Route::post('/{child}', 'storeCustomRegularTaskTemplate');
                Route::put('/{child}/{regularTaskTemplate}', 'updateCustomRegularTaskTemplate');
                Route::delete('/{child}/{regularTaskTemplate}', 'destroyCustomRegularTskTemplate');
            });
           
        });
        Route::controller(RewardController::class)
        ->prefix('rewards')
        ->group(function(){
            Route::delete('/{child}/{childReward}', 'destroy');
            Route::post('/{child}', 'store');
            Route::put('/{child}/{childReward}', 'update');
            Route::get('/{child}', 'index');
            Route::delete('/image/{child}/{childReward}', 'detachImage');
            Route::post('/image/{child}/{childReward}', 'attachImage');
            
        });
        Route::controller(RewardController::class)
        ->prefix('rewards')
        ->group(function(){
            Route::delete('general_avalable_tasks', 'destroyGeneralAvalableTasks');
            Route::get('general_avalable_tasks', 'getGeneralAvalableTasks');
            Route::post('general_avalable_tasks', 'storeGeneralAvalableTasks');
           
        });
    });
});

// Route::middleware('auth:sanctum')->group(function () {
//     // Parent part
//     Route::prefix('parent')
//         ->name('parent.')
//         ->middleware('can:is_parent')
//         ->group(function(){
//             Route::controller(ParentsChildrenController::class)->group(function(){
//                 Route::get('/children', 'index')->name('children.index');
//                 Route::delete('/children/{child}', 'detach')->name('children.detach');
//                 Route::get('/children/attach', 'generateAttachCode')->name('children.attach');
//             });
            // Route::apiResource('/tasks', ParentTaskController::class);
            // Route::apiResource('/rewards', RewardController::class);
            // Route::apiResource('reward.image', RewardImageController::class)
            // ->only(['create', 'store', 'destroy']);
    // });
    // Child part
    // Route::prefix('parent')
    //     ->name('parent.')
    //     ->middleware('can:is_child')
    //     ->group(function(){
            
    // });
// });
