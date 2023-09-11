<?php

use App\Http\Controllers\API\Client\AuthController;
use App\Http\Controllers\API\Client\IssueController;
use Illuminate\Support\Facades\Route;

Route::group([
    "prefix"=>"clients"
],function (){
    Route::group([
        "prefix" => "auth"
    ], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('otp', [AuthController::class, 'otp']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forget', [AuthController::class, 'forget']);
        Route::post('reset', [AuthController::class, 'reset']);
    });
    Route::group([
        "prefix" => "news"
    ], function () {
        Route::get('/', [NewsController::class, 'index']);
    });
    Route::group([
        "middleware" => "ClientAuth"
    ], function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::delete('/', [ProfileController::class, 'destroy']);
        Route::post('/changePassword', [ProfileController::class, 'changePassword']);
        Route::group([
            "prefix" => "notifications"
        ], function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::delete('/', [NotificationController::class, 'destroy']);
        });
        Route::group([
            "prefix" => "issues"
        ], function () {
            Route::get('/', [IssueController::class, 'index']);
            Route::post('/', [IssueController::class, 'store']);
        });
        Route::group([
            "prefix" => "home"
        ], function () {
            Route::get('/', [HomeController::class, 'index']);
        });
    });
});
