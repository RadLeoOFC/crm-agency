# Promocodes Module

***

## Description
Модуль **Promocodes** управляет промокодами и правилами их применения: глобально, для конкретной площадки или для конкретного прайс‑листа. Поддерживает проценты и фиксированные скидки, окна действия, лимиты использований и стэкаемость с другими скидками.

***

## Models
- `App\Models\PromoCode`
  - **Связи:**
    - `hasMany PromoRedemption` — истории списаний/использований
  - **$fillable:** `code`, `discount_type`, `discount_value`, `currency`, `max_uses`, `max_uses_per_client`, `starts_at`, `ends_at`, `min_order_amount`, `applies_to`, `platform_id`, `price_list_id`, `is_active`, `is_stackable`

***

## Controllers
- `App\Http\Controllers\PromoCodeController`
  - `index()` — список промокодов с пагинацией.
  - `create()` — форма создания (со списками платформ и прайс‑листов).
  - `store(Request $request)` — валидация и сохранение нового промокода.
  - `edit(PromoCode $promocode)` — форма редактирования.
  - `update(Request $request, PromoCode $promocode)` — валидация и обновление.
  - `destroy(PromoCode $promocode)` — удаление промокода.

***

## Views
Расположены в `resources/views/promocodes/`:
- `index.blade.php` — таблица со списком промокодов (тип, значение, окно действия, область применения, активность).
- `create.blade.php` — форма создания (динамически скрывает/показывает валюту/таргет).
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы, включает JS‑логику переключений (fixed/percent, global/platform/price_list).

***

## Routes
(Фрагмент из `routes/web.php`, права `promocodes.*`)

```php
Route::middleware(['auth','verified'])->group(function () {
    Route::middleware('permission:promocodes.view')->group(function () {
        Route::get('promocodes', [PromocodeController::class, 'index'])->name('promocodes.index');
        Route::get('promocodes/{promocode}', [PromocodeController::class, 'show'])->name('promocodes.show');
    });

    Route::middleware('permission:promocodes.create')->group(function () {
        Route::get('promocodes/create', [PromocodeController::class, 'create'])->name('promocodes.create');
        Route::post('promocodes', [PromocodeController::class, 'store'])->name('promocodes.store');
    });

    Route::middleware('permission:promocodes.edit')->group(function () {
        Route::get('promocodes/{promocode}/edit', [PromocodeController::class, 'edit'])->name('promocodes.edit');
        Route::put('promocodes/{promocode}', [PromocodeController::class, 'update'])->name('promocodes.update');
    });

    Route::delete('promocodes/{promocode}', [PromocodeController::class, 'destroy'])
        ->middleware('permission:promocodes.delete')
        ->name('promocodes.destroy');
});
```

***

## Functionality Overview
- Поддержка двух типов скидок:
  - `percent` — процент от суммы заказа (например, 10 = 10%).
  - `fixed` — фиксированная сумма в указанной валюте (`currency` обязательно для fixed).
- Ограничения и условия:
  - Окно действия: `starts_at` → `ends_at` (оба опциональны, `ends_at` ≥ `starts_at`).
  - `min_order_amount` — минимальная сумма заказа.
  - Лимиты: `max_uses` (общий), `max_uses_per_client` (на клиента).
  - Область применения (`applies_to`): `global` | `platform` | `price_list` (+ `platform_id`/`price_list_id`).
  - `is_active` — активность промокода, `is_stackable` — можно ли складывать с другими скидками.
- Списки платформ/прайс‑листов подгружаются в формах create/edit.

**Валидация (контроллер):**
- `code` — `required|string|max:64|unique:promo_codes,code` (при update: `unique:promo_codes,code,{id}`)
- `discount_type` — `required|in:percent,fixed`
- `discount_value` — `required|numeric|min:0`
- `currency` — `nullable|string|size:3` (нужна при `fixed` для корректной работы биллинга)
- `max_uses`, `max_uses_per_client` — `nullable|integer|min:1`
- `starts_at` — `nullable|date`
- `ends_at` — `nullable|date|after_or_equal:starts_at`
- `min_order_amount` — `nullable|numeric|min:0`
- `applies_to` — `required|in:global,platform,price_list`
- `platform_id` — `nullable|exists:platforms,id`
- `price_list_id` — `nullable|exists:price_lists,id`
- `is_active`, `is_stackable` — `sometimes|boolean`

***

## How It Works
1. **Список:** `index()` берёт промокоды `latest()->paginate(20)` и рендерит `index`.
2. **Создание:** `create()` подготавливает `platforms`, `pricelists` → `_form` → `store()` валидирует и создаёт запись.
3. **Редактирование:** `edit($promocode)` подаёт те же справочники → `_form` → `update()` валидирует и обновляет.
4. **Удаление:** `destroy()` удаляет промокод и возвращает к списку.
5. **UI‑логика:** в `_form.blade.php` встроен JS для скрытия/показа валюты, выбора цели применения и т.п.

***

## Notes
- **Права:** модуль закрыт за `auth`, `verified`, детализируется `permission:promocodes.*`.
- **Конфликты/стек:** при `is_stackable=false` в сервисе ценообразования следует запрещать одновременное применение с другими скидками.
- **Валюта:** для фиксированных скидок валюта обязательна и должна совпадать с расчётной валютой заказа/прайс‑листа либо приводиться конвертацией.
- **Таргетинг:** если `applies_to=platform/price_list`, убедитесь, что `platform_id/price_list_id` заполнены и согласованы (например, промо на прайс‑лист конкретной платформы).
- **Производительность:** индекс по `code`, а также составные индексы по (`applies_to`, `platform_id`, `price_list_id`) упростят фильтрацию.
