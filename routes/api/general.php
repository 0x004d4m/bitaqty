<?php

use App\Http\Controllers\API\General\CategoryController;
use App\Http\Controllers\API\General\CountryController;
use App\Http\Controllers\API\General\CreditStatusController;
use App\Http\Controllers\API\General\CreditTypeController;
use App\Http\Controllers\API\General\CurrencyController;
use App\Http\Controllers\API\General\IssueTypeController;
use App\Http\Controllers\API\General\ProductController;
use App\Http\Controllers\API\General\SubcategoryController;
use App\Http\Controllers\API\General\SupportedAccountController;
use App\Http\Controllers\API\General\TypeController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/issueTypes', [IssueTypeController::class, 'index']);
Route::get('/creditTypes', [CreditTypeController::class, 'index']);
Route::get('/creditStatuses', [CreditStatusController::class, 'index']);
Route::get('/currencies', [CurrencyController::class, 'index']);
Route::get('/supportedAccounts', [SupportedAccountController::class, 'index']);
Route::group(
    [
        "middleware" => "UserAuth"
    ],
    function () {
        Route::get('/types', [TypeController::class, 'index']);
        Route::get('/types/{id}/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}/subcategories', [SubcategoryController::class, 'index']);
        Route::get('/subcategories/{id}/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
    }
);
