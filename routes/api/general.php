<?php

use App\Http\Controllers\API\General\CountryController;
use App\Http\Controllers\API\General\IssueTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/issueTypes', [IssueTypeController::class, 'index']);
