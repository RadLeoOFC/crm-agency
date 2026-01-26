<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\PriceListRuleController;
use App\Http\Controllers\PriceOverrideController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LanguageSwitchController;
use App\Http\Controllers\TelegramWebhookController;
use App\Models\Language;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/telegram/webhook/{secret}', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('switch-language/{code}', [LanguageSwitchController::class, 'switch'])
    ->name('language.switch');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Register all create routes first
    Route::middleware('permission:users.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    });
    Route::middleware('permission:platforms.create')->group(function () {
        Route::get('platforms/create', [PlatformController::class, 'create'])->name('platforms.create');
    });
    Route::middleware('permission:clients.create')->group(function () {
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    });
    Route::middleware('permission:bookings.create')->group(function () {
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    });
    Route::middleware('permission:pricelists.create')->group(function () {
        Route::get('pricelists/create', [PriceListController::class, 'create'])->name('pricelists.create');
    });
    Route::middleware('permission:pricerules.create')->group(function () {
        Route::get('pricerules/create', [PriceListRuleController::class, 'create'])->name('pricerules.create');
    });
    Route::middleware('permission:priceoverrides.create')->group(function () {
        Route::get('priceoverrides/create', [PriceOverrideController::class, 'create'])->name('priceoverrides.create');
    });
    Route::middleware('permission:promocodes.create')->group(function () {
        Route::get('promocodes/create', [PromocodeController::class, 'create'])->name('promocodes.create');
    });
    Route::middleware('permission:slots.create')->group(function () {
        Route::get('slots/create', [SlotController::class, 'create'])->name('slots.create');
    });
    Route::middleware('permission:languages.create')->group(function () {
        Route::get('languages/create', [LanguageController::class, 'create'])->name('languages.create');
    });


    // Platforms management
    Route::middleware('permission:platforms.view')->group(function () {
        Route::get('platforms', [PlatformController::class, 'index'])->name('platforms.index');
    });
    Route::middleware('permission:platforms.create')->group(function () {
        Route::get('platforms/create', [PlatformController::class, 'create'])->name('platforms.create');
        Route::post('platforms', [PlatformController::class, 'store'])->name('platforms.store');
    });
    Route::middleware('permission:platforms.view')->group(function () {
        Route::get('platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');
    });
    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('platforms/{platform}/edit', [PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update');
    });
    Route::delete('platforms/{platform}', [PlatformController::class, 'destroy'])
        ->middleware('permission:platforms.delete')
        ->name('platforms.destroy');

    Route::resource('roles', RoleController::class);

    // Roles management
    Route::middleware('permission:roles.view')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });
    Route::middleware('permission:roles.create')->group(function () {
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('permission:roles.view')->group(function () {
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    });
    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete')
        ->name('roles.destroy');

    // Users management
    Route::middleware('permission:users.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('permission:users.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('permission:users.view')->group(function () {
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    Route::middleware('permission:users.edit')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('users.destroy');

    // Bookings management
    Route::middleware('permission:bookings.view')->group(function () {
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    });

    Route::middleware('permission:bookings.create')->group(function () {
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    });

    Route::middleware('permission:bookings.edit')->group(function () {
        Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    });

    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])
        ->middleware('permission:bookings.delete')
        ->name('bookings.destroy');

    // Clients management
    Route::middleware('permission:clients.view')->group(function () {
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    });

    Route::middleware('permission:clients.create')->group(function () {
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    });

    Route::middleware('permission:clients.edit')->group(function () {
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    });

    Route::delete('clients/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients.delete')
        ->name('clients.destroy');

    // Pricelists management
    Route::middleware('permission:pricelists.view')->group(function () {
        Route::get('pricelists', [PriceListController::class, 'index'])->name('pricelists.index');
        Route::get('pricelists/{pricelist}', [PriceListController::class, 'show'])->name('pricelists.show');
    });

    Route::middleware('permission:pricelists.create')->group(function () {
        Route::get('pricelists/create', [PriceListController::class, 'create'])->name('pricelists.create');
        Route::post('pricelists', [PriceListController::class, 'store'])->name('pricelists.store');
    });

    Route::middleware('permission:pricelists.edit')->group(function () {
        Route::get('pricelists/{pricelist}/edit', [PriceListController::class, 'edit'])->name('pricelists.edit');
        Route::put('pricelists/{pricelist}', [PriceListController::class, 'update'])->name('pricelists.update');
    });

    Route::delete('pricelists/{pricelist}', [PriceListController::class, 'destroy'])
        ->middleware('permission:pricelists.delete')
        ->name('pricelists.destroy');

    Route::post('/pricelists/{pricelist}/generate-slots', [PriceListController::class, 'generateSlots'])
        ->middleware('permission:pricelists.generateSlots')
        ->name('pricelists.generateSlots');

    // Price rules
    Route::middleware('permission:pricerules.view')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules', [PriceListRuleController::class, 'index'])
            ->name('pricerules.index');
    });

    Route::middleware('permission:pricerules.create')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules/create', [PriceListRuleController::class, 'create'])
            ->name('pricerules.create');
        Route::post('/pricelists/{pricelist}/pricerules', [PriceListRuleController::class, 'store'])
            ->name('pricerules.store');
    });

    Route::middleware('permission:pricerules.edit')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules/{rule}/edit', [PriceListRuleController::class, 'edit'])
            ->name('pricerules.edit');
        Route::put('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'update'])
            ->name('pricerules.update');
    });

    Route::delete('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'destroy'])
        ->middleware('permission:pricerules.delete')
        ->name('pricerules.destroy');

    // Price overrides
    Route::middleware('permission:priceoverrides.view')->group(function () {
        Route::get('/pricelists/{pricelist}/priceoverrides', [PriceOverrideController::class, 'index'])
            ->name('priceoverrides.index');
    });

    Route::middleware('permission:priceoverrides.create')->group(function () {
        Route::get('/pricelists/{pricelist}/priceoverrides/create', [PriceOverrideController::class, 'create'])
            ->name('priceoverrides.create');
        Route::post('/pricelists/{pricelist}/priceoverrides', [PriceOverrideController::class, 'store'])
            ->name('priceoverrides.store');
    });

    Route::middleware('permission:priceoverrides.edit')->group(function () {
        Route::get('/pricelists/{pricelist}/priceoverrides/{override}/edit', [PriceOverrideController::class, 'edit'])
            ->name('priceoverrides.edit');
        Route::put('/pricelists/{pricelist}/priceoverrides/{override}', [PriceOverrideController::class, 'update'])
            ->name('priceoverrides.update');
    });

    Route::delete('/pricelists/{pricelist}/priceoverrides/{override}', [PriceOverrideController::class, 'destroy'])
        ->middleware('permission:priceoverrides.delete')
        ->name('priceoverrides.destroy');

    // Promocodes management
    Route::middleware('permission:promocodes.view')->group(function () {
        Route::get('promocodes', [PromocodeController::class, 'index'])->name('promocodes.index');
        Route::get('promocodes/{promocode}', [PromocodeController::class, 'show'])->name('promocodes.show');
    });

    Route::middleware('permission:promocodes.create')->group(function () {
        Route::get('promocodes/create', [PromocodeController::class, 'create'])->name('promocodes.create');
        Route::post('promocodes', [PromocodeController::class, 'store'])->name('promocodes.store');
    });

    Route::middleware('permission:promocodes.edit')->group(function () {
        Route::get('promocodes/{promocode}/edit', [PromocodeController::class, 'edit'])->name('promocodes.edit');
        Route::put('promocodes/{promocode}', [PromocodeController::class, 'update'])->name('promocodes.update');
    });

    Route::delete('promocodes/{promocode}', [PromocodeController::class, 'destroy'])
        ->middleware('permission:promocodes.delete')
        ->name('promocodes.destroy');

    // Slots management
    Route::middleware('permission:slots.view')->group(function () {
        Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
        Route::get('slots/{slot}', [SlotController::class, 'show'])->name('slots.show');
    });

    Route::middleware('permission:slots.create')->group(function () {
        Route::get('slots/create', [SlotController::class, 'create'])->name('slots.create');
        Route::post('slots', [SlotController::class, 'store'])->name('slots.store');
    });

    Route::middleware('permission:slots.edit')->group(function () {
        Route::get('slots/{slot}/edit', [SlotController::class, 'edit'])->name('slots.edit');
        Route::put('slots/{slot}', [SlotController::class, 'update'])->name('slots.update');
    });

    Route::delete('slots/{slot}', [SlotController::class, 'destroy'])
        ->middleware('permission:slots.delete')
        ->name('slots.destroy');

    // Languages management
    Route::middleware('permission:languages.view')->group(function () {
        Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::get('languages/{language}', [LanguageController::class, 'show'])->name('languages.show');
    });

    Route::middleware('permission:languages.create')->group(function () {
        Route::get('languages/create', [LanguageController::class, 'create'])->name('languages.create');
        Route::post('languages', [LanguageController::class, 'store'])->name('languages.store');
    });

    Route::middleware('permission:languages.edit')->group(function () {
        Route::get('languages/{language}/edit', [LanguageController::class, 'edit'])->name('languages.edit');
        Route::put('languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
    });

    Route::delete('languages/{language}', [LanguageController::class, 'destroy'])
        ->middleware('permission:languages.delete')
        ->name('languages.destroy');

    Route::get('/help', fn () => view('user_guide'))->name('help.user');

});

require __DIR__.'/auth.php';
