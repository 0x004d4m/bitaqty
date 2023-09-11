<?php

use App\Http\Controllers\API\General\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'index']);
