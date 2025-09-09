<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZipCodeController; 
use App\Http\Controllers\CountyController;
 use App\Http\Controllers\MaterialController; 
 use App\Http\Controllers\AddonController; 
use App\Http\Controllers\PricingController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth'])->group(function () {
//     Route::resource('zipcodes', ZipCodeController::class);
//     Route::resource('counties', CountyController::class);
//     Route::resource('materials', MaterialController::class);
//     Route::resource('addons', AddonController::class);
//     Route::resource('pricings', PricingController::class);

//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


require __DIR__.'/auth.php';
