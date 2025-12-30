# Bookings Module

***

## Description
Модулът **Bookings** управлява създаването, преглеждането, редактирането и изтриването на резервации за рекламни публикувания. Резервацията съхранява базова/финална цена, валута, приложен промокод и статус на сделката.

***

## Models
- `App\Models\Booking`  
  **Връзки:**  
  - `belongsTo Client`  
  - `belongsTo Platform`  
  - `belongsTo Slot (nullable)`

  **Ключови полета:**
  - `platform_id`, `client_id`, `slot_id (nullable)`
  - `starts_at`, `ends_at`
  - `status` (`pending`, `confirmed`, `cancelled`, `completed`)
  - `notes (nullable)`
  - `list_price`, `discount_amount`, `price`, `currency`, `promo_code_id (nullable)`

  **Casts:**
  - `starts_at`, `ends_at` → `datetime`
  - парични полета → `decimal:2`

> Бележка: `show` използва `$booking->promoCode`. Добавете релация:
> ```php
> public function promoCode(){ return $this->belongsTo(\App\Models\PromoCode::class, 'promo_code_id'); }
> ```

***

## Controllers
- `App\Http\Controllers\BookingController`

  **Методи:**
  - `index()` — списък (пагинация) с `client`, `platform`, `slot`.
  - `create()` — форма за създаване (избор на клиент/платформа).
  - `store(Request, PricingService)` — валидация, транзакция; при избран слот — `lockForUpdate()`. Калкулация на цена чрез **PricingService::quote($priceList, $start, $end, $promo, $clientId)**, създаване на резервация и актуализация на капацитет.
  - `show(Booking)` — детайли на резервация; зарежда и списък за навигация.
  - `edit(Booking)` — форма за редакция.
  - `update(Request, Booking, PricingService)` — валидация, забрана за редакция при `confirmed/completed`, преизчисление, транзакционни корекции на стар/нов слот.
  - `destroy(Booking)` — изтриване.

***

## Views
В `resources/views/bookings/`:
- `index.blade.php`
- `create.blade.php`
- `edit.blade.php`
- `_form.blade.php`
- `show.blade.php`

***

## Routes
Дефинирани в `routes/web.php`:

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
- `index()` – списък на резервации с клиент/платформа/слот.
- `create()` – избор на клиент/платформа/слот или свободен интервал.
- `store()` – валидация, калкулация чрез `PricingService`, запис в транзакция; при слот — атомарен инкремент на `used_capacity` и статус `booked`, когато капацитетът е достигнат.
- `show()` – детайлен изглед: платформа, клиент, период, цени, промокод, статус.
- `edit()` – форма за редакция; `update()` преизчислява и коригира слотовете транзакционно.
- `destroy()` – изтриване.

**Валидация (основни правила):**
- `platform_id`, `client_id` — задължителни, трябва да съществуват.
- `slot_id` — по избор, но ако е подаден — трябва да съществува.
- Ако няма `slot_id`, `starts_at`/`ends_at` са задължителни (`ends_at` след `starts_at`) и се тълкуват в TZ на активния прайс лист.
- `promo_code` — низ до 64 символа.
- `status` — едно от `pending, confirmed, cancelled, completed` (по избор при създаване).

***

## How It Works
1. Избор на платформа/клиент → след това `slot_id` или `starts_at`/`ends_at` (в TZ на прайс листа).
2. Контролерът намира **активния прайс лист**: `platform->priceLists()->where('is_active', true)->first()`.
3. **PricingService::quote($priceList, $start, $end, $promo, $clientId)**:
   - избира override или rule,
   - проверява капацитет и лимити на промокода,
   - връща `list_price`, `discount`, `final_price`, `currency`, `promo_code_id`.
4. В транзакция:
   - при слот: `lockForUpdate()`, проверка `isAvailable()`, инкремент `used_capacity`, евентуално статус `booked`.
   - създаване/обновяване на `Booking` с иммутабилни парични стойности.
5. В `update()`:
   - забранена редакция при `confirmed/completed`,
   - при смяна на слот — освобождаване на стар и заемане на нов (и двата с `lockForUpdate()`).

***

## Notes
- **Права:** `auth`, `verified`, `permission:bookings.*`. За клиент/партньор — Policies „само своите“.
- **TZ & прайс лист:** `starts_at/ends_at` се интерпретират по `timezone` на прайс листа.
- **Заключвания:** промените в капацитет са само в `DB::transaction` с `lockForUpdate()`.
- **Промокод:** запис на redemption при `confirmed` (или според процеса); добавете `promoCode` релация.
- **Цена:** `list_price`, `discount_amount`, `price`, `currency` се фиксират в резервацията.
- **Статуси:** редакция е забранена при `confirmed/completed`.
- **Производителност:** индекси на `status`, `starts_at`, `platform_id`; кеш на активен прайс лист/правила при нужда.
