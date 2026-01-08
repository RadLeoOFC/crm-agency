# Roles Module

---

## Description

Модуль **Roles** управляет ролями и правами доступа (Spatie Permissions) в CRM: создание ролей, назначение им набора `permissions`, редактирование и удаление. Используется для разграничения доступа к модулям (`users.*`, `platforms.*`, `clients.*`, `bookings.*`, `pricelists.*`, `pricerules.*`, `priceoverrides.*`, `promocodes.*`, `slots.*`, `roles.*`, `languages.*`).

---

## Models

-   `Spatie\Permission\Models\Role` — роль.
-   `Spatie\Permission\Models\Permission` — право.

> Собственных Eloquent‑моделей в проекте для ролей/прав не требуется — используются модели из пакета Spatie.

---

## Controllers

-   `App\Http\Controllers\RoleController`
    -   `index()` — список ролей с их правами.
    -   `create()` — форма создания, загрузка всех доступных `Permission`.
    -   `store(Request $request)` — валидация, создание роли, `syncPermissions()`.
    -   `edit(Role $role)` — форма редактирования роли и её прав.
    -   `update(Request $request, Role $role)` — валидация, обновление имени, `syncPermissions()`.
    -   `destroy(Role $role)` — удаление роли.

---

## Views

В `resources/views/roles/`:

-   `index.blade.php` — таблица ролей, бейджи с правами, действия.
-   `create.blade.php` — форма создания роли + чекбоксы всех прав.
-   `edit.blade.php` — форма редактирования роли + чекбоксы прав.

---

## Routes

(Фрагмент из `routes/web.php`, защищено правами `roles.*`)

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

-   CRUD ролей.
-   Массовое назначение прав роли через `syncPermissions()`.
-   Валидация:
    -   `name` — обязателен, уникален.
    -   `permissions` — массив строк, каждая существует в таблице `permissions` (по `name`).
-   Flash‑уведомления об успехе операций.

---

## How It Works

1. **Index**: `Role::with('permissions')->get()` → таблица `roles.index`.
2. **Create**: грузим все `Permission`, рендерим чекбоксы → `roles.create`.
3. **Store**: валидируем, создаём `Role`, `syncPermissions($request->permissions)` → редирект на index.
4. **Edit**: текущая роль + все `Permission` → отмечаем имеющиеся → `roles.edit`.
5. **Update**: валидируем имя, `syncPermissions()` → редирект на index.
6. **Destroy**: удаляем роль → редирект с flash.

---

## Notes

-   **Безопасность**: операции над ролями доступны только пользователям с правами `roles.*`. Обычно — `admin`/`manager` уровня.
-   **Идемпотентность**: `syncPermissions()` безопасно синхронизирует список прав, удаляя лишние и добавляя новые.
-   **Миграции Spatie**: убедитесь, что выполнены стандартные миграции пакета (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).
-   **Семантика прав**: рекомендуемый нейминг — `<resource>.<action>` (например, `bookings.view`, `bookings.create` и т.д.).
-   **Политики (Policies)**: при необходимости дополняйте проверки на уровне моделей — например, запрет удалять системные/зарезервированные роли.
