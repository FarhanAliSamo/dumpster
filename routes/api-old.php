<?php

use App\Http\Controllers\AddonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group
| assigned the "api" middleware group. Enjoy building your API!
|
*/

// Example API route to get pricing by ZIP
Route::get('/pricing/{zip}', [PricingController::class, 'getByZip']);
Route::get('/addons', [AddonController::class, 'index']);
