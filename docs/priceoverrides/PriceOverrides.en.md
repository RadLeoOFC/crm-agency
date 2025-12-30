# PriceOverrides Module

***

## Description
The **PriceOverrides** module manages date/time-specific exceptions to pricing and/or capacity for a given price list. It lets you adjust slot price and capacity on specific days and time ranges (e.g., holidays, promos).

***

## Models
- `App\Models\PriceOverride`
  - **Relations:**  
    - `belongsTo PriceList` — parent price list
  - **$fillable:** `price_list_id`, `for_date`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Notes:**  
    - In `booted()`, the model logs retrieval and compares `price_list_id` to the current route’s `pricelist` (diagnostics).

***

## Controllers
- `App\Http\Controllers\PriceOverrideController`
  - `index(PriceList $pricelist)` — list overrides for the price list; ordered by `for_date` then `starts_at`.
  - `create(PriceList $pricelist)` — creation form.
  - `store(Request $request, PriceList $pricelist)` — validate and create via `$pricelist->overrides()->create(...)`.
  - `edit(PriceList $pricelist, PriceOverride $override)` — edit form.
  - `update(Request $request, PriceList $pricelist, PriceOverride $override)` — validate and update; logs request and update details.
  - `destroy(PriceList $pricelist, PriceOverride $override)` — delete override and redirect back.

***

## Views
Located in `resources/views/priceoverrides/`:
- `index.blade.php` — list for the selected price list.
- `create.blade.php` — creation form.
- `edit.blade.php` — edit form.
- `_form.blade.php` — shared form partial.

***

## Routes
(Extract from `routes/web.php`, guarded by `priceoverrides.*` permissions)

```php
Route::middleware(['auth','verified'])->group(function () {
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
});
```

***

## Functionality Overview
- Create/update/delete date/time overrides for pricing and capacity.
- Fields:
  - `for_date` — specific date (YYYY-MM-DD).
  - `starts_at`, `ends_at` — time in `H:i` format (local to the price list logic).
  - `slot_price` — price per slot in the interval.
  - `capacity` — optional override for available capacity.
  - `is_active` — active flag.
- Listing ordered by date then start time.

**Validation (controller):**
- `for_date` — `required|date`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `nullable|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. User opens `priceoverrides.index` for a pricelist to see current overrides.
2. Create: `priceoverrides.create` → `_form.blade.php` → `priceoverrides.store` validates and creates via the relationship.
3. Edit: `priceoverrides.edit` → `_form.blade.php` → `priceoverrides.update` validates and updates.
4. Delete: `priceoverrides.destroy` removes the record and redirects back.
5. Views render monetary values in the pricelist currency, active status, and action buttons.

***

## Notes
- **Permissions:** guarded by `auth`, `verified`, and `permission:priceoverrides.*`.
- **Timezone:** `starts_at/ends_at` are times in the price list’s timezone; consider `PriceList.timezone` when generating slots.
- **Priority:** overrides should take precedence over base `PriceListRule` logic in Pricing/Slot generation.
- **Performance:** index on `(price_list_id, for_date, starts_at)` is recommended.
- **Logging:** controller logs request/update; model logs on `retrieved` (you may disable in production).
