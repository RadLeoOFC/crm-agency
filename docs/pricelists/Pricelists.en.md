# Pricelists Module

***

## Description
The **Pricelists** module manages platform-specific price lists: currency, timezone, validity period, and default slot duration. It provides full CRUD, detail view, and slot generation based on the active price list. A price list is linked to rules (`PriceListRule`) and date-specific overrides (`PriceOverride`).

***

## Models
- `App\Models\PriceList`
  - **Relations:**  
    - `belongsTo Platform` — owning platform  
    - `hasMany PriceListRule` — scheduling/price rules  
    - `hasMany PriceOverride` — date overrides  
  - **$fillable:** `platform_id`, `name`, `currency`, `is_active`, `valid_from`, `valid_to`, `timezone`, `default_slot_duration`

***

## Controllers
- `App\Http\Controllers\PriceListController`
  - `index(Request $request)` — list price lists with platform, paginated.
  - `create()` — creation form (platforms/currencies/timezones lists).
  - `store(Request $request)` — validate and persist a price list.
  - `show(PriceList $pricelist)` — detail view with rules and overrides.
  - `edit(PriceList $pricelist)` — edit form.
  - `update(Request $request, PriceList $pricelist)` — validate and update.
  - `destroy(PriceList $pricelist)` — delete a price list.
  - `generateSlots(PriceList $pricelist)` — call `SlotGeneratorService` to bulk-create slots.

***

## Views
Located in `resources/views/pricelists/`:
- `index.blade.php` — table (platform, currency, period, timezone, active).
- `create.blade.php` — creation form.
- `edit.blade.php` — edit form.
- `_form.blade.php` — shared form partial (links to rules/overrides in edit mode).
- `show.blade.php` — current pricelist card + list of all pricelists and a “Generate slots” button.

***

## Routes
(Example setup from `routes/web.php` with `pricelists.*` permissions)

```php
Route::middleware(['auth','verified'])->group(function () {

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
});
```

***

## Functionality Overview
- Manage platform pricelists: name, currency, timezone, validity period.
- Control activity (`is_active`) to mark the current pricelist for a platform.
- Configure default slot duration (`default_slot_duration`, min 5, max 480 minutes).
- Navigate to `PriceListRule` and `PriceOverride` from the form.
- Generate slots from a pricelist via `SlotGeneratorService` (button in `show`).

**Validation (controller):**
- `platform_id` — `required|exists:platforms,id`
- `name` — `required|string|max:255`
- `currency` — `required|string|size:3`
- `is_active` — `sometimes|boolean`
- `valid_from` — `nullable|date`
- `valid_to` — `nullable|date|after_or_equal:valid_from`
- `timezone` — `required|string|max:64`
- `default_slot_duration` — `required|integer|min:5|max:480`

***

## How It Works
1. **Index:** `index()` loads pricelists with platform via `with('platform')`, paginates, renders `index`.
2. **Create:** `create()` prepares platforms/currencies/timezones → `_form.blade.php` → `store()` validates and creates → redirect with “add rules/overrides” hint.
3. **Show:** `show($pricelist)` loads `platform`, `rules`, `overrides` + renders the global list. “Generate slots” button triggers `generateSlots`.
4. **Edit:** `edit()` prepares data → `update()` validates and updates.
5. **Destroy:** `destroy()` deletes and redirects to index.
6. **Generate Slots:** `generateSlots()` calls `SlotGeneratorService::generateForPriceList($pricelist)` and shows how many slots were created.

***

## Notes
- **Permissions:** behind `auth`, `verified`, and `permission:pricelists.*`. Clients/partners typically shouldn’t access this module directly.
- **Integrity:** ensure `rules/overrides` are configured before slot generation. Consider guarding against concurrent runs in the service.
- **Active pricelist:** business rule — a platform should have exactly one active pricelist for pricing. Deactivate the old one when activating a new.
- **Currency/timezone:** lists are static in controller; move to config/DB when scaling.
- **Performance:** add indexes for `platform_id`, `is_active`, and `valid_from/valid_to` to speed up “current” pricelist queries.
- **UI:** `_form.blade.php` shows quick links to rules and overrides when editing.
