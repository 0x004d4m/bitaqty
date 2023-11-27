<?php

use App\Http\Controllers\API\Vendor\AuthController;
use App\Http\Controllers\API\Vendor\CreditController;
use App\Http\Controllers\API\Vendor\IssueController;
use App\Http\Controllers\API\Vendor\MaintenanceController;
use App\Http\Controllers\API\Vendor\NewsController;
use App\Http\Controllers\API\Vendor\NotificationController;
use App\Http\Controllers\API\Vendor\OnboardingController;
use App\Http\Controllers\API\Vendor\ProfileController;
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
            // accept, reject client credit
            // list, register clients
            Route::post('/request', [CreditController::class, 'request']);
            Route::get('/qr/{number}', [CreditController::class, 'qr']);
            Route::post('/prepaid', [CreditController::class, 'prepaid']);
        });
        // Dashboard:
        // count of clients
        // count of credit requists from clients
        // sum of orders from clients
        // sum of orders profit from clients orders

        // Statistics:
        // orders with filters: (category, supcategory, product, created_at between, card number 1, card number 2, client phone, id)
        // display cost price and selling price and diffrence as totals
        // id, user, category, sub, product, prices, card number 1, card number 2, date
    });
});
