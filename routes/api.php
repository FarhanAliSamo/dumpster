<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\Api\StaticController;
use App\Http\Controllers\Api\ZipController;
use App\Http\Controllers\Api\CalculationController;

//Get static data for addons, containers, materials
Route::get('/pricing/{zip}', [PricingController::class, 'getByZip']);
Route::get('/addons', [StaticController::class, 'Addons']);
Route::get('/containers', [StaticController::class, 'Containers']);
Route::get('/materials', [StaticController::class, 'Materials']);

//Validate ZIP code
Route::Post('/validate-zip', [ZipController::class, 'validateZip']);
Route::Post('/final-price', [CalculationController::class, 'finalPrice']);