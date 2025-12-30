# PriceRules Module

***

## Description
The **PriceRules** module defines the base pricing/capacity grid per weekday and time range for a price list. These rules act as defaults and may be overridden by the `PriceOverrides` module for specific dates.

***

## Models
- `App\Models\PriceListRule`
  - **Relations:**  
    - `belongsTo PriceList` — owning price list
  - **$fillable:** `price_list_id`, `weekday`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Field notes:**  
    - `weekday` — `1..7` (Mon..Sun), `null` means “any day”  
    - `starts_at`, `ends_at` — `HH:MM` in the price list’s timezone  
    - `slot_price` — slot price for the interval  
    - `capacity` — available capacity for the interval  
    - `is_active` — active flag

***

## Controllers
- `App\Http\Controllers\PriceListRuleController`
  - `index(PriceList $pricelist)` — list rules; ordering puts `weekday=null` last, then by `weekday`, then `starts_at`.
  - `create(PriceList $pricelist)` — show creation form.
  - `store(Request $request, PriceList $pricelist)` — validate and create via `$pricelist->rules()->create(...)`.
  - `edit(PriceList $pricelist, PriceListRule $rule)` — show edit form.
  - `update(Request $request, PriceList $pricelist, PriceListRule $rule)` — validate and update.
  - `destroy(PriceList $pricelist, PriceListRule $rule)` — delete and redirect back.

***

## Views
Located in `resources/views/pricerules/`:
- `index.blade.php` — list with weekday/any, start/end time, price, capacity, active flag.
- `create.blade.php` — creation form.
- `edit.blade.php` — edit form.
- `_form.blade.php` — shared form partial.

***

## Routes
(Extract from `routes/web.php`, guarded by `pricerules.*` permissions)

```php
Route::middleware(['auth','verified'])->group(function () {
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
});
```

***

## Functionality Overview
- Base schedule and pricing configuration: `weekday`, `starts_at`–`ends_at`, `slot_price`, `capacity`.
- Supports generic rules with `weekday=null` (apply to all days unless a specific weekday rule exists).
- Toggle rule activity via `is_active`.
- Application order: **PriceOverrides > PriceRules**.

**Validation (controller):**
- `weekday` — `nullable|integer|min:1|max:7`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `required|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. User opens `pricerules.index` for a price list to see ordered rules.
2. Create: `pricerules.create` → `_form.blade.php` → `pricerules.store` validates and creates through the relationship.
3. Edit: `pricerules.edit` → `_form.blade.php` → `pricerules.update` validates and updates.
4. Delete: `pricerules.destroy` removes the record and redirects back.
5. In pricing/slot generation, the engine first checks **overrides** for the specific date; if none, it applies **rules** by `weekday` and time range (respecting the price list timezone).

***

## Notes
- **Permissions:** `auth`, `verified`, and `permission:pricerules.*`.
- **Timezone:** interpret `starts_at/ends_at` in `PriceList.timezone`.
- **Priority:** specific `weekday` takes precedence over `weekday=null`; `PriceOverrides` takes precedence over any `PriceRules`.
- **Interval overlaps:** advisable to validate overlapping intervals per `weekday` in business logic.
- **Indexes:** `(price_list_id, weekday, starts_at)` suggested for faster queries.
- **UI:** display “Any” for `weekday=null`, localize labels.
