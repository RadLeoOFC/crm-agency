# PriceOverrides Module

***

## Description
Модуль **PriceOverrides** управляет однодневными и/или временными исключениями цен/ёмкости для конкретного прайс‑листа. Используется для изменения цены и/или количества слотов в заданный день и интервал времени (например, праздники, спец‑акции).

***

## Models
- `App\Models\PriceOverride`
  - **Связи:**  
    - `belongsTo PriceList` — базовый прайс‑лист
  - **$fillable:** `price_list_id`, `for_date`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Особенности:**  
    - В `booted()` логирует загрузку модели и сравнивает `price_list_id` с текущим маршрутом (для диагностики).

***

## Controllers
- `App\Http\Controllers\PriceOverrideController`
  - `index(PriceList $pricelist)` — список overrides для прайс‑листа; сортировка по `for_date`, затем по `starts_at`.
  - `create(PriceList $pricelist)` — форма добавления.
  - `store(Request $request, PriceList $pricelist)` — валидация и создание override через связь `$pricelist->overrides()->create(...)`.
  - `edit(PriceList $pricelist, PriceOverride $override)` — форма редактирования.
  - `update(Request $request, PriceList $pricelist, PriceOverride $override)` — валидация и обновление; ведётся подробный лог запроса и апдейта.
  - `destroy(PriceList $pricelist, PriceOverride $override)` — удаление override и редирект к списку.

***

## Views
Расположены в `resources/views/priceoverrides/`:
- `index.blade.php` — таблица исключений для выбранного прайс‑листа.
- `create.blade.php` — форма создания.
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы.

***

## Routes
(Фрагмент из `routes/web.php`, права `priceoverrides.*`)

```php
Route::middleware(['auth','verified'])->group(function () {
    // Price overrides
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
- Добавление/редактирование/удаление исключений цен по датам и времени.
- Поля override:
  - `for_date` — конкретная дата (YYYY-MM-DD).
  - `starts_at`, `ends_at` — время в формате `H:i` (локальная логика прайс‑листа).
  - `slot_price` — цена за слот на интервале.
  - `capacity` — переопределение доступной ёмкости (необязательно).
  - `is_active` — активность исключения.
- Сортировка списка — по дате, затем по времени начала.

**Валидация (по контроллеру):**
- `for_date` — `required|date`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `nullable|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. Пользователь открывает `priceoverrides.index` для выбранного прайс‑листа и видит существующие исключения.
2. Создание: `priceoverrides.create` → `_form.blade.php` → `priceoverrides.store` валидирует и создаёт override через связь прайс‑листа.
3. Редактирование: `priceoverrides.edit` → `_form.blade.php` → `priceoverrides.update` валидирует и обновляет.
4. Удаление: `priceoverrides.destroy` удаляет запись, редиректит обратно к списку.
5. Представления отображают цену в валюте прайс‑листа, статус активности и действия (редактировать/удалить).

***

## Notes
- **Права:** доступ через `auth`, `verified` и `permission:priceoverrides.*`.
- **Часовой пояс:** `starts_at/ends_at` — это локальное время прайс‑листа; учитывайте `PriceList.timezone` при расчётах слотов.
- **Приоритет:** overrides должны иметь приоритет над базовыми правилами (`PriceListRule`) в Pricing/Slot генерации.
- **Производительность:** индекс по `(price_list_id, for_date, starts_at)` ускорит выборку.
- **Логирование:** контроллер ведёт подробный лог запросов/изменений; модель логирует событие `retrieved` (опционально выключить в проде).
