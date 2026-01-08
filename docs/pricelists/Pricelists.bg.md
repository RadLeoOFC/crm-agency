# Pricelists Module

***

## Description
Модулът **Pricelists** управлява ценовите листи за платформи: валута, часови пояс, период на валидност и стандартна продължителност на слот. Осигурява пълен CRUD, детайлен изглед и генериране на слотове въз основа на активния прайс лист. Прайс листът е свързан с правила (`PriceListRule`) и изключения по дати (`PriceOverride`).

***

## Models
- `App\Models\PriceList`
  - **Връзки:**  
    - `belongsTo Platform` — свързана платформа  
    - `hasMany PriceListRule` — правила за график/цени  
    - `hasMany PriceOverride` — изключения по дати  
  - **$fillable:** `platform_id`, `name`, `currency`, `is_active`, `valid_from`, `valid_to`, `timezone`, `default_slot_duration`

***

## Controllers
- `App\Http\Controllers\PriceListController`
  - `index(Request $request)` — списък с платформи, пагиниран.
  - `create()` — форма за създаване (списъци за платформи/валути/часови пояси).
  - `store(Request $request)` — валидира и записва прайс лист.
  - `show(PriceList $pricelist)` — детайл с правила и изключения.
  - `edit(PriceList $pricelist)` — форма за редакция.
  - `update(Request $request, PriceList $pricelist)` — валидира и обновява.
  - `destroy(PriceList $pricelist)` — изтрива прайс лист.
  - `generateSlots(PriceList $pricelist)` — извиква `SlotGeneratorService` за масово създаване на слотове.

***

## Views
В `resources/views/pricelists/`:
- `index.blade.php` — таблица (платформа, валута, период, часови пояс, активен).
- `create.blade.php` — форма за създаване.
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — споделен шаблон (линкове към rules/overrides при редакция).
- `show.blade.php` — детайл на текущия прайс лист + списък на всички и бутон „Generate slots“.

***

## Routes
(Пример от `routes/web.php` с права `pricelists.*`)

```php
Route::middleware(['auth','verified'])->group(function () {

    Route::middleware('permission:pricelists.view')->group(function () {
        Route::get('pricelists', [PriceListController::class, 'index'])->name('pricelists.index');
        Route::get('pricelists/{pricelist}', [PriceListController::class, 'show'])->name('pricelists.show');
    });

    Route::middleware('permission:pricelists.create')->group(function () {
        Route::get('pricelists/create', [PriceListController::class, 'create'])->name('pricelists.create');
        Route::post('pricelists', [PriceListController::class, 'store'])->name('pricelists.store');
    });

    Route::middleware('permission:pricelists.edit')->group(function () {
        Route::get('pricelists/{pricelist}/edit', [PriceListController::class, 'edit'])->name('pricelists.edit');
        Route::put('pricelists/{pricelist}', [PriceListController::class, 'update'])->name('pricelists.update');
    });

    Route::delete('pricelists/{pricelist}', [PriceListController::class, 'destroy'])
        ->middleware('permission:pricelists.delete')
        ->name('pricelists.destroy');

    Route::post('/pricelists/{pricelist}/generate-slots', [PriceListController::class, 'generateSlots'])
        ->middleware('permission:pricelists.generateSlots')
        ->name('pricelists.generateSlots');
});
```

***

## Functionality Overview
- Управление на прайс листи: име, валута, часови пояс, период на валидност.
- Контрол на активността (`is_active`) — текущ прайс лист за платформа.
- Настройка на продължителност на слот (`default_slot_duration`, мин 5, макс 480 мин).
- Навигация към `PriceListRule` и `PriceOverride` от формата.
- Генериране на слотове чрез `SlotGeneratorService` (бутон в `show`).

**Валидация (контролер):**
- `platform_id` — `required|exists:platforms,id`
- `name` — `required|string|max:255`
- `currency` — `required|string|size:3`
- `is_active` — `sometimes|boolean`
- `valid_from` — `nullable|date`
- `valid_to` — `nullable|date|after_or_equal:valid_from`
- `timezone` — `required|string|max:64`
- `default_slot_duration` — `required|integer|min:5|max:480`

***

## How It Works
1. **Списък:** `index()` зарежда прайс листи с платформи (`with('platform')`), пагинира и рендерира `index`.
2. **Създаване:** `create()` подготвя списъци → `_form.blade.php` → `store()` валидира и записва → редирект с подсказка „добавете правила/изключения“.
3. **Детайл:** `show($pricelist)` зарежда `platform`, `rules`, `overrides` + показва глобален списък. „Generate slots“ извиква `generateSlots`.
4. **Редакция:** `edit()` подготвя данни → `update()` валидира и обновява.
5. **Изтриване:** `destroy()` изтрива и редиректва към списъка.
6. **Генериране на слотове:** `generateSlots()` извиква `SlotGeneratorService::generateForPriceList($pricelist)` и показва колко слота са създадени.

***

## Notes
- **Права:** зад `auth`, `verified`, `permission:pricelists.*`. Клиенти/партньори обикновено нямат директен достъп.
- **Цялост:** уверете се, че `rules/overrides` са конфигурирани преди генериране. Помислете за защита от паралелно стартиране.
- **Активен прайс лист:** бизнес правило — платформата трябва да има един активен прайс лист за калкулация. Деактивирайте стария при активиране на нов.
- **Валута/часови пояси:** статични списъци в контролера; при скалиране — към конфигурация/БД.
- **Производителност:** индекси по `platform_id`, `is_active`, `valid_from/valid_to` ускоряват „текущ“ избор.
- **UI:** `_form.blade.php` показва бързи линкове към rules/overrides при редакция.
