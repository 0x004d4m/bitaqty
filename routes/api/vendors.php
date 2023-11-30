<?php

use App\Http\Controllers\API\Vendor\AuthController;
use App\Http\Controllers\API\Vendor\ClientsController;
use App\Http\Controllers\API\Vendor\ClientsCreditController;
use App\Http\Controllers\API\Vendor\ClientsOrdersController;
use App\Http\Controllers\API\Vendor\CreditController;
use App\Http\Controllers\API\Vendor\IssueController;
use App\Http\Controllers\API\Vendor\MaintenanceController;
use App\Http\Controllers\API\Vendor\NewsController;
use App\Http\Controllers\API\Vendor\NotificationController;
use App\Http\Controllers\API\Vendor\OnboardingController;
use App\Http\Controllers\API\Vendor\ProfileController;
use App\Http\Controllers\API\Vendor\StatisticsController;
use App\Http\Controllers\API\Vendor\TermsController;
use Illuminate\Support\Facades\Route;

Route::group([
    "prefix" => "vendors"
], function () {
    Route::group([
        "prefix" => "auth"
    ], function () {
        Route::post('otp', [AuthController::class, 'otp']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forget', [AuthController::class, 'forget']);
        Route::post('reset', [AuthController::class, 'reset']);
    });
    Route::get('/maintenance', [MaintenanceController::class, 'index']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/privacy_policy', [TermsController::class, 'privacy']);
    Route::get('/terms_and_conditions', [TermsController::class, 'terms']);
    Route::get('/statistics', [StatisticsController::class, 'index']);
    Route::group([
        "middleware" => "UserAuth"
    ], function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::delete('/', [ProfileController::class, 'destroy']);
        Route::put('/changePassword', [ProfileController::class, 'changePassword']);
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
        Route::group([
            "prefix" => "credits"
        ], function () {
            Route::get('/', [CreditController::class, 'index']);
            Route::post('/request', [CreditController::class, 'request']);
            Route::get('/qr/{number}', [CreditController::class, 'qr']);
            Route::post('/prepaid', [CreditController::class, 'prepaid']);
        });
        Route::group([
            "prefix" => "clients"
        ], function () {
            Route::get('/', [ClientsController::class, 'index']);
            Route::post('/', [ClientsController::class, 'add']);
            Route::put('/{id}', [ClientsController::class, 'update']); // groups
        });
        Route::group([
            "prefix" => "clients_credits"
        ], function () {
            Route::get('/', [ClientsCreditController::class, 'index']);
            Route::post('/{id}/accept', [ClientsCreditController::class, 'accept']);
            Route::post('/{id}/reject', [ClientsCreditController::class, 'reject']);
            Route::post('/', [ClientsCreditController::class, 'add']);
        });
        Route::group([
            "prefix" => "clients_orders"
        ], function () {
            Route::get('/', [ClientsOrdersController::class, 'index']);
        });

        // Profit History // new DB
    });
});
