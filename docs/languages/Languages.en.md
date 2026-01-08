# Languages Module

***

## Description
The **Languages** module manages the list of available UI/content languages in the CRM. It is used to drive localization and language switching for users/admins.

***

## Models
- `App\Models\Language` — simple entity:
  - fields: `code` (e.g., `en`, `bg`, `ru`), `name` (human‑readable);
  - `$fillable = ['code','name']` to allow mass assignment.

***

## Controllers
- `App\Http\Controllers\LanguageController`
  - `index()` — list languages.
  - `create()` — creation form.
  - `store(Request $request)` — validation (`code` unique, `name` required), create record.
  - `edit(Language $language)` — edit form.
  - `update(Request $request, Language $language)` — validate and update.
  - `destroy(Language $language)` — delete language.

***

## Views
Located in `resources/views/languages/`:
- `index.blade.php` — table with languages and actions.
- `create.blade.php` — create form.
- `edit.blade.php` — edit form.
- `_form.blade.php` — shared form partial with `code`, `name` fields.

***

## Routes
(Excerpt from `routes/web.php`, guarded by `languages.*` permissions)

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
- Full CRUD for the languages directory.
- Validation:
  - `code` — required & **unique** in `languages` table;
  - `name` — required.
- Flash success messages after operations.

***

## How It Works
1. **Index**: `Language::all()` → render table with Edit/Delete actions.
2. **Create**: show `create.blade.php` → POST to `languages.store`.
3. **Store**: validate input, `Language::create()` → redirect to index with flash.
4. **Edit**: route‑model binding provides `$language` → render `edit.blade.php`.
5. **Update**: validate (`unique:languages,code,{id}`), update → redirect with flash.
6. **Destroy**: delete and redirect with flash.

***

## Notes
- **`code` format**: prefer ISO 639‑1 lowercase codes (`en`, `bg`, `ru`). If needed, extend to full locale `xx_YY`.
- **Integration**: often used with a language switcher (`LanguageSwitchController`) and `resources/lang/*` translation files.
- **Access control**: guarded by `languages.view|create|edit|delete` permissions.
- **Minor fixes in provided code**:
  - `LanguageController::create()` contains a duplicated `return` — keep only one.
  - `resources/views/languages/edit.blade.php` should post to `route('languages.update', $language)` (pass the model), otherwise the route will not resolve.
