# Languages Module

***

## Description
Модуль **Languages** управляет справочником доступных языков интерфейса/контента в CRM. Используется для отображения локализованных надписей и выбора языка пользователем/администратором.

***

## Models
- `App\Models\Language` — простая сущность языка:
  - поля: `code` (например, `en`, `bg`, `ru`), `name` (человекочитаемое название);
  - `$fillable = ['code','name']` для массового сохранения.

***

## Controllers
- `App\Http\Controllers\LanguageController`
  - `index()` — список языков.
  - `create()` — форма добавления.
  - `store(Request $request)` — валидация (`code` уникален, `name` обязателен), создание записи.
  - `edit(Language $language)` — форма редактирования.
  - `update(Request $request, Language $language)` — валидация и обновление записи.
  - `destroy(Language $language)` — удаление языка.

***

## Views
В каталоге `resources/views/languages/`:
- `index.blade.php` — таблица языков (code, name) и действия.
- `create.blade.php` — форма создания.
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы с полями `code`, `name`.

***

## Routes
(Фрагмент из `routes/web.php`, защищено правами `languages.*`)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:languages.view')->group(function () {
        Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::get('languages/{language}', [LanguageController::class, 'show'])->name('languages.show');
    });
    Route::middleware('permission:languages.create')->group(function () {
        Route::get('languages/create', [LanguageController::class, 'create'])->name('languages.create');
        Route::post('languages', [LanguageController::class, 'store'])->name('languages.store');
    });
    Route::middleware('permission:languages.edit')->group(function () {
        Route::get('languages/{language}/edit', [LanguageController::class, 'edit'])->name('languages.edit');
        Route::put('languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
    });
    Route::delete('languages/{language}', [LanguageController::class, 'destroy'])
        ->middleware('permission:languages.delete')
        ->name('languages.destroy');
});
```

***

## Functionality Overview
- CRUD для справочника языков.
- Валидация:
  - `code` — обязателен и **уникален** в таблице `languages`;
  - `name` — обязателен.
- Flash‑уведомления об успехе операций.

***

## How It Works
1. **Index**: `Language::all()` → выводим таблицу с действиями «Редактировать/Удалить».
2. **Create**: отрисовываем форму (`create.blade.php`) → отправка на `languages.store`.
3. **Store**: валидируем запрос, `Language::create()` → редирект на index с flash‑сообщением.
4. **Edit**: грузим модель через route‑model binding → рендер `edit.blade.php`.
5. **Update**: валидируем (`unique:languages,code,{id}`), обновляем → редирект с flash.
6. **Destroy**: удаляем запись → редирект с flash.

***

## Notes
- **Рекомендации по `code`**: используйте коды ISO 639‑1 в нижнем регистре (`en`, `bg`, `ru`). При необходимости можно расширить до формата `xx_YY` (локаль).
- **Интеграция**: модуль часто используется вместе со свитчером языка (`LanguageSwitchController`) и файлами переводов `resources/lang/*`.
- **Права доступа**: операции защищены `languages.view|create|edit|delete`.
- **UI‑замечания по исходному коду**:
  - В `LanguageController::create()` лишний дублирующий `return view('languages.create');` (можно оставить один).
  - В `resources/views/languages/edit.blade.php` экшен формы должен быть `route('languages.update', $language)` (передать параметр), иначе 404.
