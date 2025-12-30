# PriceRules Module

***

## Description
Модуль **PriceRules** (правила прайс‑листа) задаёт базовую сетку цен и ёмкости по дням недели и временным интервалам. Эти правила применяются как «по умолчанию» и могут быть переопределены модулем `PriceOverrides` для отдельных дат.

***

## Models
- `App\Models\PriceListRule`
  - **Связи:**  
    - `belongsTo PriceList` — прайс‑лист, к которому относится правило
  - **$fillable:** `price_list_id`, `weekday`, `starts_at`, `ends_at`, `slot_price`, `capacity`, `is_active`
  - **Пояснения по полям:**  
    - `weekday` — `1..7` (понедельник..воскресенье), `null` — «любой день»  
    - `starts_at`, `ends_at` — время интервала в формате `HH:MM` (локально для таймзоны прайс‑листа)  
    - `slot_price` — цена слота в интервале  
    - `capacity` — доступная ёмкость (кол-во размещений) для интервала  
    - `is_active` — флаг активности правила

***

## Controllers
- `App\Http\Controllers\PriceListRuleController`
  - `index(PriceList $pricelist)` — список правил для прайс‑листа; сортировка: сначала по наличию `weekday` (с `null` в конце), затем `weekday`, затем `starts_at`.
  - `create(PriceList $pricelist)` — форма добавления правила.
  - `store(Request $request, PriceList $pricelist)` — валидация и создание через `$pricelist->rules()->create(...)`.
  - `edit(PriceList $pricelist, PriceListRule $rule)` — форма редактирования правила.
  - `update(Request $request, PriceList $pricelist, PriceListRule $rule)` — валидация и обновление.
  - `destroy(PriceList $pricelist, PriceListRule $rule)` — удаление правила и редирект назад к списку.

***

## Views
Расположены в `resources/views/pricerules/`:
- `index.blade.php` — таблица правил (день недели/любой, время начала/окончания, цена, ёмкость, активность).
- `create.blade.php` — форма создания.
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы.

***

## Routes
(Фрагмент из `routes/web.php`, права `pricerules.*`)

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
- Базовая конфигурация расписания и стоимости: день недели (`weekday`), интервал (`starts_at`–`ends_at`), `slot_price`, `capacity`.
- Поддержка «универсальных» правил c `weekday=null` (применяются ко всем дням, если нет более конкретного).
- Управление активностью (`is_active`), чтобы быстро выключать/включать правило.
- Порядок применения: **PriceOverrides > PriceRules** (overrides перекрывают правила).

**Валидация (контроллер):**
- `weekday` — `nullable|integer|min:1|max:7`
- `starts_at` — `required|date_format:H:i`
- `ends_at` — `required|date_format:H:i|after:starts_at`
- `slot_price` — `required|numeric|min:0`
- `capacity` — `required|integer|min:0`
- `is_active` — `sometimes|boolean`

***

## How It Works
1. Пользователь открывает `pricerules.index` для выбранного прайс‑листа и видит список правил в порядке применения.
2. Создание: `pricerules.create` → `_form.blade.php` → `pricerules.store` валидирует данные и создаёт правило через связь прайс‑листа.
3. Редактирование: `pricerules.edit` → `_form.blade.php` → `pricerules.update` валидирует и обновляет запись.
4. Удаление: `pricerules.destroy` удаляет запись и возвращает к списку.
5. На уровне расчёта цены/генерации слотов движок ищет подходящие **overrides** на конкретную дату и, если их нет, применяет **rules** по `weekday` и времени интервала (с учётом таймзоны прайс‑листа).

***

## Notes
- **Права:** доступ через `auth`, `verified` и `permission:pricerules.*`.
- **Таймзона:** `starts_at/ends_at` интерпретируются в `PriceList.timezone` — важно для правильного совпадения интервалов.
- **Приоритетность:** конкретные `weekday` перекрывают `weekday=null`, а `PriceOverrides` перекрывают любые `PriceRules`.
- **Пересечения интервалов:** на уровне бизнес‑логики рекомендуется валидировать отсутствие конфликтующих интервалов для одного `weekday`.
- **Индексы:** `(price_list_id, weekday, starts_at)` ускорят выборку правил.
- **UI:** в списке допускается отображение «Любой» для `weekday=null`; локализуйте подписи для удобства оператора.
