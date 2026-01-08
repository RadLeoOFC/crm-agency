# Slots Module

***

## Description
The **Slots** module manages time windows (slots) for publishing/placement on platforms. A slot is a time interval with a price, status, and capacity. It can be booked by one or multiple bookings until the capacity is reached.

***

## Models
- `App\Models\Slot`
  - **Relations:**
    - `belongsTo Platform`
    - `belongsTo PriceList`
    - `hasMany Booking`
  - **$fillable:** `platform_id`, `price_list_id`, `starts_at`, `ends_at`, `price`, `status`, `capacity`, `used_capacity`
  - **$casts:** `starts_at: datetime`, `ends_at: datetime`, `price: decimal:2`, `capacity: integer`, `used_capacity: integer`
  - **Helpers:**
    - `isAvailable(): bool` — `status=available` and `used_capacity < capacity`

***

## Controllers
- `App\Http\Controllers\SlotController`
  - `index(Request $request)` — list with filters (platform, status); pagination.
  - `edit(Slot $slot)` — edit form (platform/pricelist pickers).
  - `update(Request $request, Slot $slot)` — validate and update a slot.
  - `destroy(Slot $slot)` — delete a slot.

***

## Views
Located in `resources/views/slots/`:
- `index.blade.php` — table with filters and pagination.
- `edit.blade.php` — slot edit form.

***

## Routes
(Extract from `routes/web.php`, guarded by `slots.*` permissions)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:slots.view')->group(function () {
        Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
        Route::get('slots/{slot}', [SlotController::class, 'show'])->name('slots.show'); // optional
    });
    Route::middleware('permission:slots.create')->group(function () {
        Route::get('slots/create', [SlotController::class, 'create'])->name('slots.create'); // if implemented
        Route::post('slots', [SlotController::class, 'store'])->name('slots.store');        // if implemented
    });
    Route::middleware('permission:slots.edit')->group(function () {
        Route::get('slots/{slot}/edit', [SlotController::class, 'edit'])->name('slots.edit');
        Route::put('slots/{slot}', [SlotController::class, 'update'])->name('slots.update');
    });
    Route::delete('slots/{slot}', [SlotController::class, 'destroy'])
        ->middleware('permission:slots.delete')
        ->name('slots.destroy');
});
```

> Note: The current controller doesn’t implement `create/store/show`. Slot creation can be handled via a generator service based on pricelist rules (e.g., `SlotGeneratorService`) or an admin UI per the specs.

***

## Functionality Overview
- Browse and filter slots by platform and status.
- Edit slot: platform, optional pricelist, time window, price, status, capacity, used capacity.
- Delete slot.
- Statuses: `available`, `reserved`, `booked`, `cancelled`.
- Validation on update:
  - `platform_id` — `required|exists:platforms,id`
  - `price_list_id` — `nullable|exists:price_lists,id`
  - `starts_at` — `required|date`
  - `ends_at` — `required|date|after:starts_at`
  - `price` — `required|numeric|min:0`
  - `status` — `required|in:available,reserved,booked,cancelled`
  - `capacity` — `required|integer|min:0`
  - `used_capacity` — `required|integer|min:0|lte:capacity`

***

## How It Works
1. **Index:** builds query `Slot::with(['platform','priceList'])->orderBy('starts_at','desc')`, applies filters (`platform_id`, `status`), paginates, renders `index`.
2. **Edit:** loads `platforms`, `pricelists` and renders `edit` form.
3. **Update:** validates input (see above), updates the record, redirects to index with a success flash.
4. **Destroy:** deletes a slot; business rules (Policies/Services) should prevent deleting slots with active bookings if needed.

***

## Notes
- **Permissions:** protected by `auth`, `verified`, and granular `permission:slots.*`.
- **Consistency:** ensure `used_capacity <= capacity`; when setting status to `booked`, capacity should be satisfied; editing “occupied” slots can be guarded by Policies.
- **Performance:** consider indexes on (`platform_id`, `starts_at`), on (`status`), and composite (`platform_id`, `status`, `starts_at`). Periodic archiving of old slots is recommended.
- **Generation:** slot grids are typically generated from `PriceListRule` and `PriceOverride` via a dedicated service (“Generate Slots” in the pricelists module).