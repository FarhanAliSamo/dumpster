<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\Api\StaticController;

Route::get('/pricing/{zip}', [PricingController::class, 'getByZip']);
Route::get('/addons', [StaticController::class, 'Addons']);
Route::get('/containers', [StaticController::class, 'Containers']);
// Route::get('/containers', [::class, 'index']);
