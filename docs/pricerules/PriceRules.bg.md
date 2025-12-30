# PriceRules Module

***

## Description
Модулът **PriceRules** дефинира базовата мрежа от цени/капацитет по дни от седмицата и часови интервали за прайс лист. Тези правила са по подразбиране и могат да бъдат заменени от `PriceOverrides` за конкретни дати.

***

## Models
- `App\Models\PriceListRule`
  - **Връзки:**  
    - `belongsTo PriceList` — свързан прайс лист
  - **$fillable:** `price_list_id`, `weekday`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Бележки за полетата:**  
    - `weekday` — `1..7` (Пн..Нд), `null` означава „всеки ден“  
    - `starts_at`, `ends_at` — `HH:MM` в часовия пояс на прайс листа  
    - `slot_price` — цена за интервала  
    - `capacity` — допустим капацитет за интервала  
    - `is_active` — статус

***

## Controllers
- `App\Http\Controllers\PriceListRuleController`
  - `index(PriceList $pricelist)` — списък от правила; подреждане: `weekday=null` накрая, после `weekday`, после `starts_at`.
  - `create(PriceList $pricelist)` — форма за създаване.
  - `store(Request $request, PriceList $pricelist)` — валидира и създава чрез `$pricelist->rules()->create(...)`.
  - `edit(PriceList $pricelist, PriceListRule $rule)` — форма за редакция.
  - `update(Request $request, PriceList $pricelist, PriceListRule $rule)` — валидира и обновява.
  - `destroy(PriceList $pricelist, PriceListRule $rule)` — изтрива и връща към списъка.

***

## Views
В `resources/views/pricerules/`:
- `index.blade.php` — списък (ден/всеки, начало/край, цена, капацитет, активност).
- `create.blade.php` — форма за създаване.
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — общ темплейт.

***

## Routes
(Откъс от `routes/web.php`, защитен с `pricerules.*` права)

```php
Route::middleware(['auth','verified'])->group(function () {
    Route::middleware('permission:pricerules.view')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules', [PriceListRuleController::class, 'index'])
            ->name('pricerules.index');
    });

    Route::middleware('permission:pricerules.create')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules/create', [PriceListRuleController::class, 'create'])
            ->name('pricerules.create');
        Route::post('/pricelists/{pricelist}/pricerules', [PriceListRuleController::class, 'store'])
            ->name('pricerules.store');
    });

    Route::middleware('permission:pricerules.edit')->group(function () {
        Route::get('/pricelists/{pricelist}/pricerules/{rule}/edit', [PriceListRuleController::class, 'edit'])
            ->name('pricerules.edit');
        Route::put('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'update'])
            ->name('pricerules.update');
    });

    Route::delete('/pricelists/{pricelist}/pricerules/{rule}', [PriceListRuleController::class, 'destroy'])
        ->middleware('permission:pricerules.delete')
        ->name('pricerules.destroy');
});
```

***

## Functionality Overview
- Базова конфигурация: `weekday`, `starts_at`–`ends_at`, `slot_price`, `capacity`.
- Поддръжка на общи правила с `weekday=null` (важат за всички дни, освен ако има по‑конкретно).
- Изключване/включване с `is_active`.
- Ред на прилагане: **PriceOverrides > PriceRules**.

**Валидация (контролер):**
- `weekday` — `nullable|integer|min:1|max:7`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `required|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. Потребителят отваря `pricerules.index` и вижда подредени правила.
2. Създаване: `pricerules.create` → `_form.blade.php` → `pricerules.store` валидира и записва през релацията.
3. Редакция: `pricerules.edit` → `_form.blade.php` → `pricerules.update` валидира и обновява.
4. Изтриване: `pricerules.destroy` премахва записа и връща към списъка.
5. При ценообразуване/генериране първо се проверяват **overrides**, и ако няма — се прилагат **rules** по `weekday` и интервал (в часовия пояс на прайс листа).

***

## Notes
- **Права:** `auth`, `verified`, `permission:pricerules.*`.
- **Часови пояс:** интерпретация на времето по `PriceList.timezone`.
- **Приоритет:** конкретен `weekday` има предимство пред `weekday=null`; `PriceOverrides` има предимство пред `PriceRules`.
- **Припокривания:** препоръчително е да валидирате липса на припокриващи се интервали за даден `weekday`.
- **Индекси:** `(price_list_id, weekday, starts_at)` ускоряват заявките.
- **UI:** показвайте „Всеки“ при `weekday=null` и локализирайте етикетите.
