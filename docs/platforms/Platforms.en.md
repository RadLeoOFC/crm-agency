# Platforms Module

---

## Description

The **Platforms** module manages the catalog of advertising platforms (Telegram channels, YouTube, Facebook, websites). It provides CRUD, stores base properties (currency, timezone, active flag), and maintains relations to price lists, slots, and bookings. On creation it notifies administrators and managers.

---

## Models

-   `App\Models\Platform`
    **Relations:**

    -   `hasMany PriceList`
    -   `hasMany Slot`
    -   `hasMany Booking`

    **Fillable (`$fillable`):**

    -   `name`, `type` (`telegram|youtube|facebook|website`), `description`
    -   `currency` (one of `Platform::$currencies`: `USD|EUR|BGN`)
    -   `timezone` (from `DateTimeZone::listIdentifiers()`)
    -   `is_active` (boolean)

    **Static data:**

    -   `public static $currencies = ['USD'=>'US Dollar','EUR'=>'Euro','BGN'=>'Bulgarian Lev'];`

---

## Controllers

-   `App\Http\Controllers\PlatformController`
    -   `index(Request $request)` — paginated list of platforms.
    -   `create()` — creation form (passes timezone list).
    -   `store(Request $request)` — validates, creates record, and sends `PlatformCreationNotification` to all users with roles `admin` and `manager`.
    -   `show(Platform $platform)` — detail page loading counts of `priceLists/slots/bookings` via `loadCount()`.
    -   `edit(Platform $platform)` — edit form (timezone list included).
    -   `update(Request $request, Platform $platform)` — validation and update.
    -   `destroy(Platform $platform)` — delete.

---

## Views

Located in `resources/views/platforms/`:

-   `index.blade.php` — platforms table with status badges and actions.
-   `create.blade.php` — creation form (name/type/description/currency/timezone/is_active).
-   `edit.blade.php` — edit form.

---

## Routes

(Example from `routes/web.php` with `platforms.*` permissions)

```php
Route::middleware(['auth','verified'])->group(function () {
    Route::middleware('permission:platforms.view')->group(function () {
        Route::get('platforms', [PlatformController::class, 'index'])->name('platforms.index');
        Route::get('platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');
    });

    Route::middleware('permission:platforms.create')->group(function () {
        Route::get('platforms/create', [PlatformController::class, 'create'])->name('platforms.create');
        Route::post('platforms', [PlatformController::class, 'store'])->name('platforms.store');
    });

    Route::middleware('permission:platforms.edit')->group(function () {
        Route::get('platforms/{platform}/edit', [PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update');
    });

    Route::delete('platforms/{platform}', [PlatformController::class, 'destroy'])
        ->middleware('permission:platforms.delete')
        ->name('platforms.destroy');
});
```

---

## Functionality Overview

-   Platforms CRUD (type/name/description/currency/timezone/is_active).
-   List pagination (`latest('id')->paginate(15)->withQueryString()`).
-   Currency options provided by `Platform::$currencies`.
-   Timezones from `DateTimeZone::listIdentifiers()`.
-   Related entities: price lists, slots, bookings
-   On create, send `PlatformCreationNotification` to all users with roles `admin` and `manager`.

**Validation (per controller):**

-   `name` — `required|string|max:255`
-   `type` — `required|in:telegram,youtube,facebook,website`
-   `description` — `nullable|string|max:1000`
-   `currency` — `required|string|in:USD,EUR,BGN` (built from keys of `Platform::$currencies`)
-   `timezone` — `required|string|in:<all DateTimeZone identifiers>`
-   `is_active` — `boolean`

---

## How It Works

1. Authorized user opens `platforms.index` to see the table of platforms with active/inactive badges.
2. Creation: `platforms.create` → fill form → `platforms.store` validates and persists.
3. After creation, controller gets the current user (`auth()->user()`) and notifies users with `admin` and `manager` roles via `notify(new PlatformCreationNotification($creator))`.
4. Viewing: `platforms.show` loads counters of related entities for quick analytics.
5. Editing/Deleting: `platforms.edit` / `platforms.update` and `platforms.destroy` respectively.

---

## Notes

-   **Access control:** routes protected by `auth`, `verified`, `permission:platforms.*`.
-   **Reference data:** keep currencies centralized (as now) or move to config/DB when scaling.
-   **Form data:** in Blade, ensure proper IDs, use `old()` defaults from `$platform`; for the active switch use `old('is_active', $platform->is_active ?? true)`.
-   **Indexing:** consider indexes on `type`, `is_active`, and possibly `(type, is_active)`.
-   **Auditing:** consider Spatie Activity Log to record platform changes.
