<?php

use App\Http\Controllers\LandingPageController;
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
Route::get('/', function () {
    return view('welcome');
});
