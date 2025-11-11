<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NpiController;
use App\Http\Controllers\NpinumberController;
// use App\Http\Controllers\NpinumberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', controller:CartController::class);

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});
// addaded by imran NIAZ 

Route::get('/npi', [NpiController::class, 'index'])->name('npi.index');
Route::post('/npi', [NpiController::class, 'store'])->name('npi.store');


Route::get('/npiupload', [NpinumberController::class, 'uploadPage'])->name('npi.upload');
Route::post('/npiupload', [NpinumberController::class, 'bulkUpload'])->name('npi.bulk');


require __DIR__.'/auth.php';


