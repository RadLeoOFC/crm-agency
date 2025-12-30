# Pricelists Module

***

## Description
Модуль **Pricelists** управляет прайс‑листами площадок: валюта прайс‑листа, таймзона, период действия, дефолтная длительность слота. Поддерживает CRUD, просмотр и генерацию слотов на основе активного прайс‑листа. Прайс‑лист связывается с правилами (`PriceListRule`) и исключениями (`PriceOverride`).

***

## Models
- `App\Models\PriceList`
  - **Связи**:  
    - `belongsTo Platform` — площадка, к которой относится прайс‑лист  
    - `hasMany PriceListRule` — правила расписания/цен  
    - `hasMany PriceOverride` — переопределения на конкретные даты  
  - **$fillable**: `platform_id`, `name`, `currency`, `is_active`, `valid_from`, `valid_to`, `timezone`, `default_slot_duration`

***

## Controllers
- `App\Http\Controllers\PriceListController`
  - `index(Request $request)` — список прайс‑листов с площадкой, пагинация.
  - `create()` — форма создания (списки площадок/валют/таймзон).
  - `store(Request $request)` — валидация и сохранение прайс‑листа.
  - `show(PriceList $pricelist)` — детальная страница с правилами/исключениями.
  - `edit(PriceList $pricelist)` — форма редактирования.
  - `update(Request $request, PriceList $pricelist)` — валидация и обновление.
  - `destroy(PriceList $pricelist)` — удаление прайс‑листа.
  - `generateSlots(PriceList $pricelist)` — вызов `SlotGeneratorService` для пакетного создания слотов.

***

## Views
Расположены в `resources/views/pricelists/`:
- `index.blade.php` — таблица прайс‑листов (площадка, валюта, период, таймзона, активность).
- `create.blade.php` — форма создания.
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы (вкл. ссылки на правила и overrides при редактировании).
- `show.blade.php` — карточка текущего прайс‑листа + список всех, кнопка «Generate slots».

***

## Routes
(Пример конфигурации в `routes/web.php`, с пермишенами `pricelists.*`)

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
- Ведение прайс‑листов для площадок: название, валюта, таймзона, период действия.
- Управление активностью (`is_active`) — определяет «текущий» прайс‑лист платформы.
- Настройка дефолтной длительности слота (`default_slot_duration`, мин: 5, макс: 480 минут).
- Просмотр и переход к правилам (`PriceListRule`) и переопределениям (`PriceOverride`) из формы.
- Генерация слотов из прайс‑листа через `SlotGeneratorService` (кнопка в `show`).

**Валидация (в контроллере):**
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
1. **Список:** `index()` получает прайс‑листы с площадками (`with('platform')`), пагинирует и рендерит `index`.
2. **Создание:** `create()` формирует списки площадок, валют и таймзон → `_form.blade.php` → `store()` валидирует и создаёт запись → редирект с подсказкой «добавьте правила/исключения».
3. **Просмотр:** `show($pricelist)` загружает `platform`, `rules`, `overrides` + рендерит таблицу всех прайс‑листов (для навигации). Кнопка «Generate slots» вызывает `generateSlots`.
4. **Редактирование:** `edit()` подготавливает данные для формы → `update()` валидирует и обновляет.
5. **Удаление:** `destroy()` удаляет запись и редиректит к списку.
6. **Генерация слотов:** `generateSlots()` вызывает `SlotGeneratorService::generateForPriceList($pricelist)` и показывает число созданных слотов.

***

## Notes
- **Права доступа:** все маршруты за `auth`, `verified`, детализируются через `permission:pricelists.*`. Клиентам/партнёрам прямой доступ обычно не требуется.
- **Целостность:** перед генерацией слотов убедитесь, что настроены `rules`/`overrides`. Конкурентность генерации — через сервис (при необходимости защитить от повторного запуска).
- **Активный прайс‑лист:** бизнес‑правило — на платформе в момент расчёта цены должен быть 1 активный прайс‑лист. При активации нового — деактивируйте старый.
- **Валюта/таймзона:** списки валют и таймзон в контроллере статичны; при масштабировании — вынести в конфиг/БД.
- **Производительность:** индексы по `platform_id`, `is_active`, `valid_from/valid_to` упростят выборку «текущего» листа.
- **UI:** во фрагменте `_form.blade.php` при наличии ID показываются ссылки на `rules` и `overrides` для ускорения настройки.
