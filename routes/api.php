<?php

use App\Http\Controllers\API\Client\AuthController as ClientAuthController;
use App\Http\Controllers\API\General\CountryController;
use App\Http\Controllers\API\Vendor\AuthController as VendorAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/clients/auth/register', [ClientAuthController::class, 'register']);
Route::post('/clients/auth/otp', [ClientAuthController::class, 'otp']);
Route::post('/clients/auth/login', [ClientAuthController::class, 'login']);
Route::post('/clients/auth/forget', [ClientAuthController::class, 'forget']);
Route::post('/clients/auth/reset', [ClientAuthController::class, 'reset']);

Route::post('/vendors/auth/register', [VendorAuthController::class, 'register']);
Route::post('/vendors/auth/otp', [AuthController::class, 'otp']);
Route::post('/vendors/auth/login', [AuthController::class, 'login']);
Route::post('/vendors/auth/forget', [AuthController::class, 'forget']);
Route::post('/vendors/auth/reset', [AuthController::class, 'reset']);

Route::get('/countries', [CountryController::class, 'index']);
