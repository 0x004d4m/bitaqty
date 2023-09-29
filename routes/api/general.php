<?php

use App\Http\Controllers\API\General\CountryController;
use App\Http\Controllers\API\General\CreditStatusController;
use App\Http\Controllers\API\General\CreditTypeController;
use App\Http\Controllers\API\General\CurrencyController;
use App\Http\Controllers\API\General\IssueTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/issueTypes', [IssueTypeController::class, 'index']);
Route::get('/creditTypes', [CreditTypeController::class, 'index']);
Route::get('/creditStatuses', [CreditStatusController::class, 'index']);
Route::get('/currencies', [CurrencyController::class, 'index']);
