# Roles Module

---

## Description

Модулът **Roles** управлява ролите и правата (Spatie Permissions): създаване на роли, присвояване на `permissions`, редакция и изтриване. Използва се за контрол на достъпа в модулите (`users.*`, `platforms.*`, `clients.*`, `bookings.*`, `pricelists.*`, `pricerules.*`, `priceoverrides.*`, `promocodes.*`, `slots.*`, `roles.*`, `languages.*`).

---

## Models

-   `Spatie\Permission\Models\Role` — роля.
-   `Spatie\Permission\Models\Permission` — право.

> Не са нужни отделни модели — използват се тези от Spatie.

---

## Controllers

-   `App\Http\Controllers\RoleController`
    -   `index()` — списък на ролите с правата им.
    -   `create()` — форма за създаване; зарежда всички `Permission`.
    -   `store(Request $request)` — валидация, създаване, `syncPermissions()`.
    -   `edit(Role $role)` — форма за редакция на ролята и правата ѝ.
    -   `update(Request $request, Role $role)` — валидация, обновяване на име, `syncPermissions()`.
    -   `destroy(Role $role)` — изтриване на роля.

---

## Views

В `resources/views/roles/`:

-   `index.blade.php` — таблица с роли, бейджове с права, действия.
-   `create.blade.php` — форма за създаване + чекбокси за права.
-   `edit.blade.php` — форма за редакция + чекбокси за права.

---

## Routes

(Извадка от `routes/web.php`, защитено с права `roles.*`)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:roles.view')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });
    Route::middleware('permission:roles.create')->group(function () {
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete')
        ->name('roles.destroy');
});
```

---

## Functionality Overview

-   Пълен CRUD за роли.
-   Масово присвояване на права чрез `syncPermissions()`.
-   Валидация:
    -   `name` — задължително и уникално.
    -   `permissions` — масив от низове; всеки трябва да съществува в таблица `permissions` (по `name`).
-   Flash съобщения за успех.

---

## How It Works

1. **Index**: `Role::with('permissions')->get()` → изглед `roles.index`.
2. **Create**: зареждаме всички `Permission` → чекбокси → `roles.create`.
3. **Store**: валидация, `Role::create()`, `syncPermissions($request->permissions)` → пренасочване към index.
4. **Edit**: зареждаме роля + всички `Permission`, маркираме текущите → `roles.edit`.
5. **Update**: валидация на име, `syncPermissions()` → пренасочване.
6. **Destroy**: изтриваме роля → пренасочване с flash.

---

## Notes

-   **Сигурност**: само потребители с права `roles.*` управляват ролите.
-   **Идемпотентност**: `syncPermissions()` синхронизира безопасно множеството права.
-   **Миграции Spatie**: уверете се, че таблиците съществуват (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).
-   **Именуване на права**: конвенция `<resource>.<action>` (напр. `bookings.view`, `bookings.create` и др.).
-   **Policies**: добавете политики, ако трябва да забраните изтриване на системни/резервирани роли.
