# Bookings Module

***

## Description
Модуль **Bookings** отвечает за создание, просмотр, редактирование и удаление броней размещений на площадках. Бронь фиксирует рассчитанную стоимость (листовую и финальную), валюту, применённый промокод и статус сделки.

***

## Models
- `App\Models\Booking`  
  **Связи:**  
  - `belongsTo Client`  
  - `belongsTo Platform`  
  - `belongsTo Slot (nullable)`

  **Поля (ключевые):**
  - `platform_id`, `client_id`, `slot_id (nullable)`
  - `starts_at`, `ends_at`
  - `status` (`pending`, `confirmed`, `cancelled`, `completed`)
  - `notes (nullable)`
  - `list_price`, `discount_amount`, `price`, `currency`, `promo_code_id (nullable)`

  **Касты:**
  - `starts_at`, `ends_at` → `datetime`
  - денежные поля → `decimal:2`

> Примечание: во вьюхе `show` используется `$booking->promoCode`. Добавьте связь в модель:
> ```php
> public function promoCode(){ return $this->belongsTo(\App\Models\PromoCode::class, 'promo_code_id'); }
> ```

***

## Controllers
- `App\Http\Controllers\BookingController`

  **Методы:**
  - `index()` — список броней (пагинация), подгружает `client`, `platform`, `slot`.
  - `create()` — форма создания (списки клиентов/площадок).
  - `store(Request $request, PricingService $pricingService)` — валидация, транзакция, при выборе слота — `lockForUpdate()`, расчёт цены через **PricingService::quote($priceList, $start, $end, $promo, $clientId)**, создание брони и обновление ёмкости слота.
  - `show(Booking $booking)` — просмотр детали (доп. список броней для навигации).
  - `edit(Booking $booking)` — форма редактирования.
  - `update(Request $request, Booking $booking, PricingService $pricingService)` — валидация, запрет редактирования для `confirmed/completed`, пересчёт цены, транзакция, коррекция старого/нового слота с блокировками.
  - `destroy(Booking $booking)` — удаление.

***

## Views
Расположено в `resources/views/bookings/`:
- `index.blade.php`
- `create.blade.php`
- `edit.blade.php`
- `_form.blade.php`
- `show.blade.php`

***

## Routes
Определены в `routes/web.php`:

```php
Route::middleware(['auth','verified'])->group(function () {
    // просмотр
    Route::middleware('permission:bookings.view')->group(function () {
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    });
    // создание
    Route::middleware('permission:bookings.create')->group(function () {
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    });
    // редактирование
    Route::middleware('permission:bookings.edit')->group(function () {
        Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    });
    // удаление
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])
        ->middleware('permission:bookings.delete')
        ->name('bookings.destroy');
});
```

***

## Functionality Overview
- `index()` – выводит список броней с клиентом/площадкой/слотом.
- `create()` – форма выбора клиента/площадки/слота или произвольного интервала.
- `store()` – валидация, расчёт цены, создание брони в транзакции; при бронировании слота — атомарное увеличение `used_capacity` и, при достижении лимита, смена статуса слота на `booked`.
- `show()` – карточка брони: платформа, клиент, период, цены (листовая → финальная), промокод и статус.
- `edit()` – форма изменения; `update()` пересчитывает цену, корректирует слоты (освобождение старого, занятие нового) в транзакции.
- `destroy()` – удаляет бронь.

**Валидация (основное):**
- `platform_id`, `client_id` — required + `exists`.
- `slot_id` — nullable + `exists`.
- Если `slot_id` отсутствует → обязательны `starts_at`/`ends_at` (`ends_at` после `starts_at`), времена интерпретируются в TZ активного прайс-листа площадки.
- `promo_code` — строка до 64 символов.
- `status` — одно из: `pending, confirmed, cancelled, completed` (опционально при создании).

***

## How It Works
1. Выбор площадки/клиента → дальше либо слот, либо произвольный интервал (в TZ прайс-листа).
2. Контроллер находит **активный прайс-лист**: `platform->priceLists()->where('is_active', true)->first()`.
3. **PricingService::quote($priceList, $start, $end, $promo, $clientId)**:
   - ищет подходящий override или rule,
   - проверяет capacity для окна и лимиты промокода,
   - возвращает `list_price`, `discount`, `final_price`, `currency`, `promo_code_id`.
4. В транзакции:
   - при слоте: `lockForUpdate()`, проверка `isAvailable()`, `used_capacity++`, при достижении лимита → `status='booked'`.
   - создаётся/обновляется `Booking` с фиксацией всех денежных полей.
5. В `update()`:
   - бронь со статусом `confirmed/completed` не редактируем,
   - если слот поменялся — старый освобождаем, новый занимаем (оба под `lockForUpdate()`).

***

## Notes
- **Права доступа:** `auth`, `verified`, `permission:bookings.*`. Для кабинетов клиента/партнёра используйте Policies «только свои».
- **TZ и прайс-лист:** `starts_at/ends_at` трактуются в `timezone` активного прайс-листа.
- **Блокировки:** любые изменения ёмкости слота — строго в `DB::transaction` с `lockForUpdate()`.
- **Промокоды:** фиксируйте списания при `confirmed` (или по вашему процессу); добавьте связь `promoCode` в модель.
- **Иммутабельность цены:** в брони сохраняются `list_price`, `discount_amount`, `price`, `currency`.
- **Статусы:** правка запрещена для `confirmed/completed`.
- **Производительность:** индексы на `status`, `starts_at`, `platform_id`; кешируйте активный прайс-лист/правила при надобности.
