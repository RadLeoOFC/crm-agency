# Languages Module

***

## Description
Модулът **Languages** управлява списъка с налични езици за интерфейса/съдържанието в CRM. Използва се за локализация и превключване на езика от потребители/администратори.

***

## Models
- `App\Models\Language` — проста ентити:
  - полета: `code` (напр. `en`, `bg`, `ru`), `name` (четимо име);
  - `$fillable = ['code','name']` позволява mass assignment.

***

## Controllers
- `App\Http\Controllers\LanguageController`
  - `index()` — списък с езици.
  - `create()` — форма за добавяне.
  - `store(Request $request)` — валидация (`code` уникален, `name` задължително), създаване.
  - `edit(Language $language)` — форма за редакция.
  - `update(Request $request, Language $language)` — валидация и обновяване.
  - `destroy(Language $language)` — изтриване.

***

## Views
В `resources/views/languages/`:
- `index.blade.php` — таблица с езици и действия.
- `create.blade.php` — форма за създаване.
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — общ фрагмент с полета `code` и `name`.

***

## Routes
(Извадка от `routes/web.php`, защитено с права `languages.*`)

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
- Пълен CRUD за справочника с езици.
- Валидация:
  - `code` — задължителен и **уникален** в таблица `languages`;
  - `name` — задължителен.
- Flash съобщения за успех.

***

## How It Works
1. **Index**: `Language::all()` → таблица с бутони Редакция/Изтриване.
2. **Create**: показва `create.blade.php` → POST към `languages.store`.
3. **Store**: валидация, `Language::create()` → пренасочване към index с flash.
4. **Edit**: route‑model binding предоставя `$language` → рендер `edit.blade.php`.
5. **Update**: валидация (`unique:languages,code,{id}`), update → пренасочване.
6. **Destroy**: изтриване → пренасочване с flash.

***

## Notes
- **Формат на `code`**: препоръчително ISO 639‑1 в малки букви (`en`, `bg`, `ru`). При нужда — локал `xx_YY`.
- **Интеграция**: често се използва с превключвател на езика (`LanguageSwitchController`) и файлове `resources/lang/*`.
- **Права**: контролира се чрез `languages.view|create|edit|delete`.
- **Дребни корекции в предоставения код**:
  - В `LanguageController::create()` има дублирано `return view(...)` — оставете само едно.
  - В `resources/views/languages/edit.blade.php` екшънът трябва да е `route('languages.update', $language)` (с параметър), иначе маршрутът няма да съвпадне.
