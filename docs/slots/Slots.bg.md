# Slots Module

***

## Description
Модулът **Slots** управлява времеви интервали (слотове) за публикуване/позициониране в платформите. Слотът е интервал с цена, статус и капацитет и може да бъде резервиран до запълване на капацитета.

***

## Models
- `App\Models\Slot`
  - **Връзки:**
    - `belongsTo Platform`
    - `belongsTo PriceList`
    - `hasMany Booking`
  - **$fillable:** `platform_id`, `price_list_id`, `starts_at`, `ends_at`, `price`, `status`, `capacity`, `used_capacity`
  - **$casts:** `starts_at: datetime`, `ends_at: datetime`, `price: decimal:2`, `capacity: integer`, `used_capacity: integer`
  - **Помощни методи:**
    - `isAvailable(): bool` — `status=available` и `used_capacity < capacity`

***

## Controllers
- `App\Http\Controllers\SlotController`
  - `index(Request $request)` — списък с филтри по платформа и статус; пагинация.
  - `edit(Slot $slot)` — форма за редакция (избор на платформа/прайс лист).
  - `update(Request $request, Slot $slot)` — валидира и обновява.
  - `destroy(Slot $slot)` — изтрива слот.

***

## Views
В `resources/views/slots/`:
- `index.blade.php` — таблица със слотове, филтри и пагинация.
- `edit.blade.php` — форма за редакция на слота.

***

## Routes
(Откъс от `routes/web.php`, защита с права `slots.*`)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:slots.view')->group(function () {
        Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
        Route::get('slots/{slot}', [SlotController::class, 'show'])->name('slots.show'); // по избор
    });
    Route::middleware('permission:slots.create')->group(function () {
        Route::get('slots/create', [SlotController::class, 'create'])->name('slots.create'); // ако е имплементирано
        Route::post('slots', [SlotController::class, 'store'])->name('slots.store');        // ако е имплементирано
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

***

## Functionality Overview
- Преглед и филтриране на слотове по платформа и статус.
- Редакция: платформа, опционален прайс лист, времеви интервал, цена, статус, капацитет и използван капацитет.
- Изтриване на слот.
- Статуси: `available`, `reserved`, `booked`, `cancelled`.
- Валидация при update:
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
1. **Index:** заявка `Slot::with(['platform','priceList'])->orderBy('starts_at','desc')`, прилага филтри (`platform_id`, `status`), пагинира и рендерира `index`.
2. **Edit:** зарежда справочници `platforms`, `pricelists` и визуализира `edit` формата.
3. **Update:** валидира входа (виж горе), обновява записа и редирект към index със `success` съобщение.
4. **Destroy:** изтрива слот; бизнес правила (Policies/Services) могат да забранят изтриване при активни резервации.

***

## Notes
- **Права:** модулът е зад `auth`, `verified`, с детайлни `permission:slots.*`.
- **Консистентност:** гарантирайте `used_capacity <= capacity`; при статус `booked` капацитетът трябва да е покрит; промени по „заети“ слотове да минават през Policies.
- **Производителност:** индекси върху (`platform_id`, `starts_at`), (`status`) и съставен (`platform_id`, `status`, `starts_at`). Архивиране на стари слотове препоръчително.
- **Генерация:** слотовете обичайно се генерират от `PriceListRule` и `PriceOverride` чрез специален сервиз („Generate Slots“ в модула за прайс листове).