# Roles Module

---

## Description

The **Roles** module manages application roles and permissions (Spatie Permissions): create roles, assign a set of `permissions`, edit, and delete. It enforces access across modules (`users.*`, `platforms.*`, `clients.*`, `bookings.*`, `pricelists.*`, `pricerules.*`, `priceoverrides.*`, `promocodes.*`, `slots.*`, `roles.*`, `languages.*`).

---

## Models

-   `Spatie\Permission\Models\Role` — role entity.
-   `Spatie\Permission\Models\Permission` — permission entity.

> No custom Eloquent models are required; Spatie models are used directly.

---

## Controllers

-   `App\Http\Controllers\RoleController`
    -   `index()` — list roles with permissions.
    -   `create()` — form to create, loads all `Permission` records.
    -   `store(Request $request)` — validate, create role, `syncPermissions()`.
    -   `edit(Role $role)` — edit form with role's permissions.
    -   `update(Request $request, Role $role)` — validate, update name, `syncPermissions()`.
    -   `destroy(Role $role)` — delete role.

---

## Views

Located in `resources/views/roles/`:

-   `index.blade.php` — roles table, permission badges, actions.
-   `create.blade.php` — role creation form + permission checkboxes.
-   `edit.blade.php` — role edit form + permission checkboxes.

---

## Routes

(Excerpt from `routes/web.php`, guarded by `roles.*` permissions)

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

-   Full CRUD for roles.
-   Bulk assignment of permissions via `syncPermissions()`.
-   Validation:
    -   `name` is required and unique.
    -   `permissions` is an array of strings; each must exist in `permissions` table (by `name`).
-   Flash success messages.

---

## How It Works

1. **Index**: `Role::with('permissions')->get()` → render `roles.index`.
2. **Create**: load all `Permission` → checkbox list → `roles.create`.
3. **Store**: validate, `Role::create()`, `syncPermissions($request->permissions)` → redirect to index.
4. **Edit**: load role + all `Permission`, mark selected → `roles.edit`.
5. **Update**: validate name, `syncPermissions()` → redirect.
6. **Destroy**: delete role → redirect with flash.

---

## Notes

-   **Security**: only users granted `roles.*` may manage roles.
-   **Idempotency**: `syncPermissions()` safely synchronizes the set (adds/removes differences).
-   **Spatie Migrations**: ensure default tables exist (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).
-   **Permission naming**: follow `<resource>.<action>` convention (e.g., `bookings.view`, `bookings.create`, ...).
-   **Policies**: add model policies if you need to forbid deleting reserved/system roles.
