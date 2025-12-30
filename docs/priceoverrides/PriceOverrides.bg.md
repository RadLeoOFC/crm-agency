# PriceOverrides Module

***

## Description
Модулът **PriceOverrides** управлява изключения по дати/часове за даден прайс лист — корекция на цена и/или капацитет за конкретни дни и интервали (напр. празници, промоции).

***

## Models
- `App\Models\PriceOverride`
  - **Връзки:**  
    - `belongsTo PriceList` — свързан прайс лист
  - **$fillable:** `price_list_id`, `for_date`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Бележки:**  
    - В `booted()` моделът логва зареждането и сравнява `price_list_id` с `pricelist` от текущия маршрут (диагностика).

***

## Controllers
- `App\Http\Controllers\PriceOverrideController`
  - `index(PriceList $pricelist)` — списък от изключения; сортиране по `for_date`, после `starts_at`.
  - `create(PriceList $pricelist)` — форма за създаване.
  - `store(Request $request, PriceList $pricelist)` — валидиране и създаване чрез `$pricelist->overrides()->create(...)`.
  - `edit(PriceList $pricelist, PriceOverride $override)` — форма за редакция.
  - `update(Request $request, PriceList $pricelist, PriceOverride $override)` — валидиране и обновяване; логиране на заявката и обновените данни.
  - `destroy(PriceList $pricelist, PriceOverride $override)` — изтриване и редирект обратно.

***

## Views
В `resources/views/priceoverrides/`:
- `index.blade.php` — списък за избрания прайс лист.
- `create.blade.php` — форма за създаване.
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — общ темплейт на формата.

***

## Routes
(Откъс от `routes/web.php`, защитен с права `priceoverrides.*`)

```php
Route::middleware(['auth','verified'])->group(function () {
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
- Създаване/редакция/изтриване на изключения по дати и часове.
- Полета:
  - `for_date` — конкретна дата (YYYY-MM-DD).
  - `starts_at`, `ends_at` — час в `H:i` формат (локален за прайс листа).
  - `slot_price` — цена на слот за интервала.
  - `capacity` — опционален override на капацитета.
  - `is_active` — статус.
- Списъкът е сортиран по дата и начален час.

**Валидация (контролер):**
- `for_date` — `required|date`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `nullable|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. Потребителят отваря `priceoverrides.index` за даден прайс лист и вижда изключенията.
2. Създаване: `priceoverrides.create` → `_form.blade.php` → `priceoverrides.store` валидира и създава през релацията.
3. Редакция: `priceoverrides.edit` → `_form.blade.php` → `priceoverrides.update` валидира и обновява.
4. Изтриване: `priceoverrides.destroy` премахва записа и връща към списъка.
5. Изгледите показват стойности в валутата на прайс листа, активност и бутони за действия.

***

## Notes
- **Права:** `auth`, `verified`, `permission:priceoverrides.*`.
- **Часови пояс:** `starts_at/ends_at` се тълкуват в часовия пояс на `PriceList.timezone` при генериране на слотове/цени.
- **Приоритет:** overrides имат предимство пред базовите правила `PriceListRule` при ценообразуване/генериране.
- **Производителност:** индекс върху `(price_list_id, for_date, starts_at)` е препоръчителен.
- **Логове:** контролерът и моделът логват ключови операции; може да ги ограничите в продукция.
