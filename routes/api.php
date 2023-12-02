<?php

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\ChildrenSide\ChildInfoController;
use App\Http\Controllers\Api\ChildrenSide\ChildRewardController;
use App\Http\Controllers\Api\ChildrenSide\TaskController;
use App\Http\Controllers\Api\ParentSide\AdultController;
use App\Http\Controllers\Api\ParentSide\AdultTypeController;
use App\Http\Controllers\Api\ParentSide\CustomRegularTaskController;
use App\Http\Controllers\Api\ParentSide\SocialLoginController;
use App\Http\Controllers\Api\ParentSide\StatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentSide\ChildController;
use App\Http\Controllers\Api\ParentSide\OneDayTaskController;
use App\Http\Controllers\Api\ParentSide\ProofTypeController;
use App\Http\Controllers\Api\ParentSide\ReferalController;
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
    Route::post('logout', 'logout')->middleware('auth:sanctum');

    Route::post('forgot-password', 'sendPasswordResetToken');
    Route::post('reset-password', 'resetPassword')->name('password.reset');
    // Route::post('update-password', 'updatePassword');
})->middleware(['guest', 'locale']);

Route::controller(SocialLoginController::class)->group(function(){
    Route::get('web-social-auth/{provider}',  'redirectToProvider');
    Route::get('web-social-auth/{provider}/callback',  'handleProviderCallback');
    Route::post('social-auth/login', 'appSocialLogin');
});

Route::middleware(['auth:sanctum', 'locale'])->group(function () {
    Route::get('/default_icon', [BaseController::class, 'getDefaultIcon']);
    Route::get('/task_icons', [BaseController::class, 'getTaskIcons']);
    Route::get('/task_icons/{icon}', [BaseController::class, 'getTaskIconById']);
    Route::prefix('adult')
    ->name('adult.')
    ->group(function() {
        Route::prefix('refer')
            ->group(function(){
                Route::get('/', [ReferalController::class,'createCode']);
                Route::post('/', [ReferalController::class,'useReferalCode']);
            });
    })
    ->group(function(){
        Route::prefix('adults')
            ->group(function(){
                Route::get('/showAdultProfile', [AdultController::class, 'showAdultProfile']);
                Route::put('/update-adult-profile', [AdultController::class, 'updateAdultProfile']);
                Route::delete('/deleteAdultProfile', [AdultController::class, 'destroy']);
                Route::put('/updateSettings', [AdultController::class, 'updateSettings']);
                Route::prefix('statistics')
                    ->group(function(){
                        Route::get('/', [StatisticController::class, 'adultStatistic']);
                    });
            });
        Route::apiResource('adult_types', AdultTypeController::class)->only(['index']);
        Route::apiResource('proof_types', ProofTypeController::class)->only(['index']);
        Route::apiResource('children', ChildController::class);
        Route::get('get_child_token/{child}', [ChildController::class, 'getAccessTokenByChild']);
        Route::controller(RegularTaskController::class)
        ->prefix('regular_tasks')
        ->group(function(){
            Route::get('/regular_task_templates/{child}', 'getRegularTaskTemplatesByChild');
            Route::put('/regular_task_templates/{child}', 'updateRegularTaskTemplates');
            Route::get('/{child}', 'getRegularTasksByChild');
            Route::post('/{regularTask}', 'updateRegularTask');
            // Route::get('/', 'getRegularTasks');
            Route::controller(CustomRegularTaskController::class)
            ->prefix('custom_tasks')
            ->group(function(){
                Route::get('/{regularTaskTemplate}', 'getCustomRegularTaskTemplateById');
                Route::post('/{child}', 'storeCustomRegularTaskTemplate');
                Route::post('/{child}/{regularTaskTemplate}', 'updateCustomRegularTaskTemplate');
                Route::delete('/{child}/{regularTaskTemplate}', 'destroyCustomRegularTaskTemplate');
            });
           
        });
        Route::controller(OneDayTaskController::class)
        ->prefix('one_day_tasks')
        ->group(function(){
            Route::get('/{child}', 'index');
            Route::post('/{child}', 'store');
            Route::get('/{child}/{oneDayTask}', 'show');
            Route::put('/{child}/{oneDayTask}', 'update');
            Route::post('/{child}/{oneDayTask}', 'update');
            Route::delete('/{child}/{oneDayTask}', 'destroy');
           
        });
        Route::controller(RewardController::class)
        ->prefix('rewards')
        ->group(function(){
            Route::delete('/{child}/{childReward}', 'destroy');
            Route::post('/{child}', 'store');
            Route::post('/{child}/{childReward}', 'update');
            Route::get('/{child}/{childReward}', 'getRewardById');
            Route::get('/{child}', 'index');
            
            Route::delete('/image/{child}/{childReward}', 'detachImage');
            Route::post('/image/{child}/{childReward}', 'attachImage');
        });
    });
    Route::prefix('child')
    ->name('child.')
    ->group(function(){
        Route::controller(ChildInfoController::class)
        ->prefix('/info')
        ->group(function(){
            Route::get('/', 'getMainInfo');
        });
        Route::controller(TaskController::class)
        ->prefix('/tasks')
        ->group(function(){
            Route::get('/', 'getAllTasks');
            Route::get('/regular_tasks/{regularTask}', 'getRegularTask');
            Route::put('/regular_tasks/{regularTask}', 'updateRegularTask');
            Route::get('/one_day_tasks/{oneDayTask}', 'getOneDayTask');
            Route::put('/one_day_tasks/{oneDayTask}', 'updateOneDayTask');
            Route::post('/store_unlock_request', 'storeTaskUnlockRequest');
            
        });
        Route::controller(ChildRewardController::class)
        ->prefix('/rewards')
        ->group(function(){
            Route::get('/', 'getAllRewards');
            Route::post('/{childReward}', 'updateReward');
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
