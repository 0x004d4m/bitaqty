<?php

use App\Http\Controllers\API\Client\VerifyEmail;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TermsController;
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

Route::get('/set-language/{locale}', [LandingPageController::class, 'setLanguage'])->name('set-language');
Route::get('/set-currency/{currency}', [LandingPageController::class, 'setCurrency'])->name('set-currency');
Route::get('/clients/verifyEmail', [VerifyEmail::class, 'check']);
Route::get('/terms/{id}', [TermsController::class, 'show']);
Route::get('/', function () {
    return view('welcome');
});
