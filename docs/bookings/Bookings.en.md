# Bookings Module

***

## Description
The **Bookings** module manages creating, viewing, editing, and deleting ad bookings. A booking stores list/final prices, currency, applied promo code, and the deal status.

***

## Models
- `App\Models\Booking`  
  **Relations:**  
  - `belongsTo Client`  
  - `belongsTo Platform`  
  - `belongsTo Slot (nullable)`

  **Key fields:**
  - `platform_id`, `client_id`, `slot_id (nullable)`
  - `starts_at`, `ends_at`
  - `status` (`pending`, `confirmed`, `cancelled`, `completed`)
  - `notes (nullable)`
  - `list_price`, `discount_amount`, `price`, `currency`, `promo_code_id (nullable)`

  **Casts:**
  - `starts_at`, `ends_at` → `datetime`
  - monetary fields → `decimal:2`

> Note: the `show` view references `$booking->promoCode`. Add the relation:
> ```php
> public function promoCode(){ return $this->belongsTo(\App\Models\PromoCode::class, 'promo_code_id'); }
> ```

***

## Controllers
- `App\Http\Controllers\BookingController`

  **Methods:**
  - `index()` — paginated list (eager loads `client`, `platform`, `slot`).
  - `create()` — creation form (client/platform selectors).
  - `store(Request, PricingService)` — validates, runs transaction; if a slot is selected, locks it with `lockForUpdate()`. Calculates price via **PricingService::quote($priceList, $start, $end, $promo, $clientId)**, creates booking, updates slot capacity.
  - `show(Booking)` — booking detail; optionally loads a list for navigation.
  - `edit(Booking)` — edit form.
  - `update(Request, Booking, PricingService)` — validation, prevent editing `confirmed/completed`, re-quote price, transactional slot capacity adjustments with locks.
  - `destroy(Booking)` — delete booking.

***

## Views
Located at `resources/views/bookings/`:
- `index.blade.php`
- `create.blade.php`
- `edit.blade.php`
- `_form.blade.php`
- `show.blade.php`

***

## Routes
Defined in `routes/web.php`:

```php
Route::middleware(['auth','verified'])->group(function () {
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
});
```

***

## Functionality Overview
- `index()` – lists bookings with related client/platform/slot.
- `create()` – pick client/platform/slot or define a custom time window.
- `store()` – validate, quote via `PricingService`, create booking in a transaction; for slot-based bookings, atomically increment `used_capacity` and set slot to `booked` when capacity is reached.
- `show()` – detail view with platform, client, time period, prices (list → final), promo, and status.
- `edit()` – edit form; `update()` re-quotes and adjusts old/new slot capacities transactionally.
- `destroy()` – remove booking.

**Validation (high-level):**
- `platform_id`, `client_id` — required & must exist.
- `slot_id` — optional & must exist when provided.
- If no `slot_id`, `starts_at`/`ends_at` required (`ends_at` after `starts_at`) and interpreted in the active price list TZ.
- `promo_code` — string up to 64 chars.
- `status` — one of `pending, confirmed, cancelled, completed` (optional on create).

***

## How It Works
1. User selects platform/client → then either a `slot_id`, or provides `starts_at`/`ends_at` (interpreted in price list TZ).
2. Controller resolves the **active price list**: `platform->priceLists()->where('is_active', true)->first()`.
3. **PricingService::quote($priceList, $start, $end, $promo, $clientId)**:
   - picks an override or rule,
   - checks capacity and promo limits,
   - returns `list_price`, `discount`, `final_price`, `currency`, `promo_code_id`.
4. In a transaction:
   - with slot: `lockForUpdate()`, `isAvailable()` check, `used_capacity++`, optionally mark `booked`.
   - create/update `Booking` with immutable monetary fields.
5. In `update()`:
   - editing is blocked for `confirmed/completed`,
   - when switching slots, release old and occupy new (both locked with `lockForUpdate()`).

***

## Notes
- **Permissions:** `auth`, `verified`, `permission:bookings.*`. Use Policies for client/partner dashboards to enforce “own records only”.
- **TZ & price list:** `starts_at/ends_at` are interpreted in the price list `timezone`.
- **Locks:** slot capacity changes must be done under `DB::transaction` with `lockForUpdate()`.
- **Promo codes:** record redemptions on `confirmed` (or per your process); add `promoCode` relation on Booking.
- **Price immutability:** `list_price`, `discount_amount`, `price`, `currency` are persisted in the booking.
- **Statuses:** editing is blocked for `confirmed/completed`.
- **Performance:** add indexes on `status`, `starts_at`, `platform_id`; consider caching active price list/rules.
