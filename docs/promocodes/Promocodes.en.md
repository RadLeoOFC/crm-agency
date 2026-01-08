# Promocodes Module

***

## Description
The **Promocodes** module manages discount codes and their scopes: global, platform‑specific, or price‑list‑specific. Supports percentage and fixed discounts, validity windows, usage limits, and stackability rules.

***

## Models
- `App\Models\PromoCode`
  - **Relations:**
    - `hasMany PromoRedemption` — redemption history
  - **$fillable:** `code`, `discount_type`, `discount_value`, `currency`, `max_uses`, `max_uses_per_client`, `starts_at`, `ends_at`, `min_order_amount`, `applies_to`, `platform_id`, `price_list_id`, `is_active`, `is_stackable`

***

## Controllers
- `App\Http\Controllers\PromoCodeController`
  - `index()` — paginated list of promocodes.
  - `create()` — creation form (platforms, pricelists).
  - `store(Request $request)` — validate and persist.
  - `edit(PromoCode $promocode)` — edit form.
  - `update(Request $request, PromoCode $promocode)` — validate and update.
  - `destroy(PromoCode $promocode)` — delete.

***

## Views
Located in `resources/views/promocodes/`:
- `index.blade.php` — table with type/value, window, scope, activity.
- `create.blade.php` — creation form (JS toggles for currency/scope).
- `edit.blade.php` — edit form.
- `_form.blade.php` — shared form partial with UI logic.

***

## Routes
(Extract from `routes/web.php`, guarded by `promocodes.*` permissions)

```php
Route::middleware(['auth','verified'])->group(function () {
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
});
```

***

## Functionality Overview
- Two discount types:
  - `percent` — percentage of order total (e.g., 10 = 10%).
  - `fixed` — fixed amount in a specified currency (currency required).
- Constraints and conditions:
  - Validity window: `starts_at` → `ends_at` (optional; `ends_at` ≥ `starts_at`).
  - `min_order_amount` — minimum order total.
  - Limits: `max_uses` (global), `max_uses_per_client` (per client).
  - Scope (`applies_to`): `global` | `platform` | `price_list` (+ `platform_id`/`price_list_id`).
  - `is_active` — active flag; `is_stackable` — whether it can be combined with others.
- Platform/pricelist lookup is provided in create/edit forms.

**Validation (controller):**
- `code` — `required|string|max:64|unique:promo_codes,code` (on update: `unique:promo_codes,code,{id}`)
- `discount_type` — `required|in:percent,fixed`
- `discount_value` — `required|numeric|min:0`
- `currency` — `nullable|string|size:3` (required for fixed discounts at billing time)
- `max_uses`, `max_uses_per_client` — `nullable|integer|min:1`
- `starts_at` — `nullable|date`
- `ends_at` — `nullable|date|after_or_equal:starts_at`
- `min_order_amount` — `nullable|numeric|min:0`
- `applies_to` — `required|in:global,platform,price_list`
- `platform_id` — `nullable|exists:platforms,id`
- `price_list_id` — `nullable|exists:price_lists,id`
- `is_active`, `is_stackable` — `sometimes|boolean`

***

## How It Works
1. **Index:** `index()` fetches codes with `latest()->paginate(20)` and renders `index`.
2. **Create:** `create()` prepares `platforms`, `pricelists` → `_form` → `store()` validates and creates.
3. **Edit:** `edit()` uses the same lookups → `_form` → `update()` validates and updates.
4. **Delete:** `destroy()` removes a code and redirects to index.
5. **UI:** `_form.blade.php` includes JS to toggle currency visibility and scope‑specific selects.

***

## Notes
- **Permissions:** protected by `auth`, `verified`, and `permission:promocodes.*`.
- **Conflicts/stacking:** with `is_stackable=false`, pricing should prevent combining with other discounts.
- **Currency:** required for fixed discounts and should match or be converted to the order/pricelist currency.
- **Targeting:** when `applies_to=platform/price_list`, ensure corresponding IDs are provided and consistent (e.g., pricelist belongs to the platform).
- **Performance:** add an index on `code` and consider composite indexes on (`applies_to`, `platform_id`, `price_list_id`).
