<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\PriceListRuleController;
use App\Http\Controllers\PriceOverrideController;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\SlotController;
use Illuminate\Support\Facades\Route;

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

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('platforms', PlatformController::class);
    Route::resource('users', UserController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('pricelists', PriceListController::class);
    Route::get('/pricelists/{pricelist}/pricerules', [PriceListRuleController::class, 'index'])
    ->name('pricerules.index');
    Route::get('/pricelists/{pricelist}/pricerules/create', [PriceListRuleController::class, 'create'])
    ->name('pricerules.create');
    Route::post('/pricelists/{pricelist}/pricerules/store', [PriceListRuleController::class, 'store'])
    ->name('pricerules.store');
    Route::get('/pricelists/{pricelist}/pricerules/{rule}/edit', [PriceListRuleController::class, 'edit'])
    ->name('pricerules.edit');
    Route::put('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'update'])
    ->name('pricerules.update');
    Route::delete('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'destroy'])
    ->name('pricerules.destroy');
    Route::get('/pricelists/{pricelist}/priceoverrides', [PriceOverrideController::class, 'index'])
    ->name('priceoverrides.index');
    Route::get('/pricelists/{pricelist}/priceoverrides/create', [PriceOverrideController::class, 'create'])
    ->name('priceoverrides.create');
    Route::post('/pricelists/{pricelist}/priceoverrides/store', [PriceOverrideController::class, 'store'])
    ->name('priceoverrides.store');
    Route::get('/pricelists/{pricelist}/priceoverrides/{override}/edit', [PriceOverrideController::class, 'edit'])
    ->name('priceoverrides.edit');
    Route::put('/pricelists/{pricelist}/priceoverrides/{override}', [PriceOverrideController::class, 'update'])
    ->name('priceoverrides.update');
    Route::delete('/pricelists/{pricelist}/priceoverrides/{override}', [PriceOverrideController::class, 'destroy'])
    ->name('priceoverrides.destroy');
    Route::post('/pricelists/{pricelist}/generate-slots', [
        App\Http\Controllers\PriceListController::class, 'generateSlots'
    ])->name('pricelists.generateSlots')->middleware('role:admin|manager');

    Route::resource('promocodes', PromocodeController::class);
    Route::resource('slots', SlotController::class);
});

require __DIR__.'/auth.php';
