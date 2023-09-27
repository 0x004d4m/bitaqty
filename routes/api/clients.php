<?php

use App\Http\Controllers\API\Client\AuthController;
use App\Http\Controllers\API\Client\CreditController;
use App\Http\Controllers\API\Client\IssueController;
use App\Http\Controllers\API\Client\NewsController;
use App\Http\Controllers\API\Client\NotificationController;
use App\Http\Controllers\API\Client\OnboardingController;
use App\Http\Controllers\API\Client\ProfileController;
use App\Http\Controllers\API\Client\TermsController;
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
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/privacy_policy', [TermsController::class, 'privacy']);
    Route::get('/terms_and_conditions', [TermsController::class, 'terms']);
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
            Route::delete('/{id}', [NotificationController::class, 'destroy']);
            Route::patch('/{id}', [NotificationController::class, 'read']);
        });
        Route::group([
            "prefix" => "issues"
        ], function () {
            Route::get('/', [IssueController::class, 'index']);
            Route::post('/', [IssueController::class, 'store']);
        });
        // Route::get('/prepaid_card', [HomeController::class, 'index']);
        Route::group([
            "middleware" => "Currency"
        ], function () {
            Route::group([
                "prefix" => "credits"
            ], function () {
                Route::get('/', [CreditController::class, 'index']);
                Route::post('/request', [CreditController::class, 'request']);
                Route::post('/send', [CreditController::class, 'send']);
                Route::get('/qr/{number}', [CreditController::class, 'qr']);
                Route::post('/prepaid', [CreditController::class, 'prepaid']);
            });
        });
    });
});
