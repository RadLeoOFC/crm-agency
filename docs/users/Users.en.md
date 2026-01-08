# Users Module

***

## Description
The **Users** module manages CRM user accounts (staff/partners): create, edit, assign roles (via Spatie Permissions), and delete. Passwords are hashed, access is enforced via `users.*` permissions.

***

## Models
- `App\Models\User`
  - Extends `Authenticatable`, uses `HasRoles` (Spatie) and `Notifiable`.
  - **$fillable:** `name`, `email`, `telegram_chat_id`, `password`
  - **$hidden:** `password`, `remember_token`
  - **casts():** `email_verified_at: datetime`, `password: hashed`

***

## Controllers
- `App\Http\Controllers\UserController`
  - `index()` — list users.
  - `show(User $user)` — show profile/details (if used).
  - `create()` — render create form + load all roles.
  - `store(Request $request)` — validate, hash password, create user, sync roles.
  - `edit(User $user)` — render edit form + roles.
  - `update(Request $request, User $user)` — validate, optionally change password, sync roles.
  - `destroy(User $user)` — delete user.

***

## Views
Located in `resources/views/users/`:
- `index.blade.php` — table with actions.
- `create.blade.php` — user create form.
- `edit.blade.php` — user edit form.
- `_form.blade.php` — shared fields (name, email, telegram_chat_id, password, roles).

***

## Routes
(Extract from `routes/web.php`, guarded by `users.*` permissions)

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:users.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    Route::middleware('permission:users.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('permission:users.edit')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('users.destroy');
});
```

***

## Functionality Overview
- Full CRUD for users.
- Role assignment via Spatie `syncRoles()` (multi-select).
- Validation:
  - create: `name|required|max:255`, `email|required|email|unique:users`, `telegram_chat_id|required|unique:users`, `password|required|min:8`
  - update: uniqueness for `email` and `telegram_chat_id` ignores current `id`, `password` is optional (not changed if empty).
- Password hashing using `Hash::make(...)`.
- Success flash messages on actions.

***

## How It Works
1. **Index:** fetch all users → `users.index` view with actions.
2. **Create:** load roles and render `_form`.
3. **Store:** validate, hash password, create `User`, `syncRoles()` if provided, redirect to index.
4. **Edit:** load user + roles, render `_form` with selected roles.
5. **Update:** validate; if password empty → do not change; update attributes; sync roles; redirect to index.
6. **Destroy:** delete user; redirect with flash message.

***

## Notes
- **Permissions:** enforced via `auth`, `verified`, and granular `permission:users.view|create|edit|delete`.
- **Roles/Policies:** only privileged users can manage roles; consider Policies to prevent deleting yourself or top‑level admins.
- **Telegram:** `telegram_chat_id` is unique — useful for Telegram notifications.
- **Security:** minimum password length, hashing, hidden fields; consider re‑verification on email change if `MustVerifyEmail` is enabled.
- **UX:** `_form` supports multi‑select roles; password field can be left blank on edit to keep the current password.
