# Slots Module

***

## Description
Модуль **Slots** управляет временными слотами публикаций/размещений на площадках. Слот — это интервал времени с ценой, статусом и ёмкостью (capacity), который может быть забронирован одной или несколькими бронями до достижения лимита.

***

## Models
- `App\Models\Slot`
  - **Связи:**
    - `belongsTo Platform`
    - `belongsTo PriceList`
    - `hasMany Booking`
  - **$fillable:** `platform_id`, `price_list_id`, `starts_at`, `ends_at`, `price`, `status`, `capacity`, `used_capacity`
  - **$casts:** `starts_at: datetime`, `ends_at: datetime`, `price: decimal:2`, `capacity: integer`, `used_capacity: integer`
  - **Хелперы:**
    - `isAvailable(): bool` — статус `available` и `used_capacity < capacity`

***

## Controllers
- `App\Http\Controllers\SlotController`
  - `index(Request $request)` — список слотов с фильтрами по площадке и статусу; пагинация.
  - `edit(Slot $slot)` — форма редактирования (выбор площадки/прайс‑листа).
  - `update(Request $request, Slot $slot)` — валидация и обновление слота.
  - `destroy(Slot $slot)` — удаление слота.

***

## Views
В `resources/views/slots/`:
- `index.blade.php` — таблица со слотами, фильтры по `platform_id`, `status`, пагинация.
- `edit.blade.php` — форма редактирования полей слота.

***

## Routes
(Фрагмент из `routes/web.php`, права `slots.*`)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:slots.view')->group(function () {
        Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
        Route::get('slots/{slot}', [SlotController::class, 'show'])->name('slots.show'); // опционально
    });
    Route::middleware('permission:slots.create')->group(function () {
        Route::get('slots/create', [SlotController::class, 'create'])->name('slots.create'); // если реализовано
        Route::post('slots', [SlotController::class, 'store'])->name('slots.store');        // если реализовано
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

> Примечание: В текущем контроллере отсутствуют методы `create/store/show`. Создание слотов может выполняться через сервис генерации из прайс‑листа (`SlotGeneratorService`) или из админки по ТЗ.

***

## Functionality Overview
- Просмотр и фильтрация слотов по площадке и статусу.
- Редактирование слота: площадка, прайс‑лист (необязательно), окно времени, цена, статус, ёмкость и использованная ёмкость.
- Удаление слота.
- Статусы: `available`, `reserved`, `booked`, `cancelled`.
- Валидация при обновлении:
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
1. **Index:** контроллер собирает запрос `Slot::with(['platform','priceList'])->orderBy('starts_at','desc')`, применяет фильтры (`platform_id`, `status`), пагинирует и рендерит `index.blade.php`.
2. **Edit:** контроллер подгружает справочники `platforms`, `pricelists` и отдаёт `edit.blade.php`.
3. **Update:** выполняет валидацию (см. выше), обновляет запись и возвращает на список со `success`‑flash.
4. **Destroy:** удаляет слот; ответственность за разруливание связанных броней/ограничений лежит на уровне бизнес‑правил (например, Policy/Service может блокировать удаление слота с активными бронями).

***

## Notes
- **Права:** весь модуль закрыт за `auth`, `verified`, детально через `permission:slots.*`.
- **Консистентность:** `used_capacity <= capacity`; при переводе слота в `booked` убедитесь, что ёмкость заполнена; при редактировании занятых слотов — ограничения через Policies.
- **Производительность:** рекомендуются индексы по (`platform_id`, `starts_at`), (`status`), а также составной (`platform_id`, `status`, `starts_at`). Для архивных данных имеет смысл партиями чистить старые слоты.
- **Генерация:** создание сетки слотов выполняется сервисом генерации на основе `PriceListRule` и `PriceOverride` (кнопка “Generate Slots” в модуле прайс‑листов).