# Promocodes Module

***

## Description
Модулът **Promocodes** управлява промо кодове и техния обхват: глобално, за платформа или за конкретен прайс лист. Поддържа процентни и фиксирани отстъпки, период на валидност, лимити за употреба и правила за комбиниране (stackability).

***

## Models
- `App\Models\PromoCode`
  - **Връзки:**
    - `hasMany PromoRedemption` — история на използванията
  - **$fillable:** `code`, `discount_type`, `discount_value`, `currency`, `max_uses`, `max_uses_per_client`, `starts_at`, `ends_at`, `min_order_amount`, `applies_to`, `platform_id`, `price_list_id`, `is_active`, `is_stackable`

***

## Controllers
- `App\Http\Controllers\PromoCodeController`
  - `index()` — списък с пагинация.
  - `create()` — форма за създаване (платформи, прайс листи).
  - `store(Request $request)` — валидира и записва.
  - `edit(PromoCode $promocode)` — форма за редакция.
  - `update(Request $request, PromoCode $promocode)` — валидира и обновява.
  - `destroy(PromoCode $promocode)` — изтрива.

***

## Views
В `resources/views/promocodes/`:
- `index.blade.php` — таблица (тип/стойност, валидност, обхват, активност).
- `create.blade.php` — форма за създаване (JS превключва валута/обхват).
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — общ темплейт с UI логика.

***

## Routes
(Откъс от `routes/web.php`, защитен с права `promocodes.*`)

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
- Два типа отстъпки:
  - `percent` — процент от стойността на поръчката (напр. 10 = 10%).
  - `fixed` — фиксирана сума в определена валута (валутата е задължителна).
- Ограничения и условия:
  - Период на валидност: `starts_at` → `ends_at` (опционални; `ends_at` ≥ `starts_at`).
  - `min_order_amount` — минимална стойност на поръчката.
  - Лимити: `max_uses` (общ), `max_uses_per_client` (за клиент).
  - Обхват (`applies_to`): `global` | `platform` | `price_list` (+ `platform_id`/`price_list_id`).
  - `is_active` — активен; `is_stackable` — може ли да се комбинира с други.
- В create/edit формите има падащи списъци за платформи и прайс листи.

**Валидация (контролер):**
- `code` — `required|string|max:64|unique:promo_codes,code` (при update: `unique:promo_codes,code,{id}`)
- `discount_type` — `required|in:percent,fixed`
- `discount_value` — `required|numeric|min:0`
- `currency` — `nullable|string|size:3` (задължителна за fixed при фактуриране)
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
1. **Списък:** `index()` зарежда кодове с `latest()->paginate(20)` и рендерира `index`.
2. **Създаване:** `create()` подготвя `platforms`, `pricelists` → `_form` → `store()` валидира и записва.
3. **Редакция:** `edit()` използва същите справочници → `_form` → `update()` валидира и обновява.
4. **Изтриване:** `destroy()` премахва кода и връща към списъка.
5. **UI:** `_form.blade.php` съдържа JS за превключване на валутата и селекторите по обхват.

***

## Notes
- **Права:** зад `auth`, `verified`, с детайлни `permission:promocodes.*`.
- **Конфликти/комбиниране:** при `is_stackable=false` ценообразуването не трябва да комбинира други отстъпки.
- **Валута:** задължителна за fixed и трябва да съвпада или да се конвертира към валутата на поръчката/прайс листа.
- **Таргетиране:** при `applies_to=platform/price_list` — валидирайте съответните ID и консистентност (напр. прайс листът е от дадената платформа).
- **Производителност:** индекс по `code` и съставни индекси по (`applies_to`, `platform_id`, `price_list_id`) са препоръчителни.
