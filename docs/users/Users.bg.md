# Users Module

***

## Description
Модулът **Users** управлява акаунтите в CRM (служители/партньори): създаване, редакция, присвояване на роли (Spatie Permissions) и изтриване. Паролите се хешират, достъпът е чрез права `users.*`.

***

## Models
- `App\Models\User`
  - Наследява `Authenticatable`, използва `HasRoles` (Spatie) и `Notifiable`.
  - **$fillable:** `name`, `email`, `telegram_chat_id`, `password`
  - **$hidden:** `password`, `remember_token`
  - **casts():** `email_verified_at: datetime`, `password: hashed`

***

## Controllers
- `App\Http\Controllers\UserController`
  - `index()` — списък с потребители.
  - `show(User $user)` — детайли/профил (ако се използва).
  - `create()` — форма за създаване + зареждане на роли.
  - `store(Request $request)` — валидация, хеширане, създаване, `syncRoles()`.
  - `edit(User $user)` — форма за редакция + роли.
  - `update(Request $request, User $user)` — валидация, по избор смяна на парола, `syncRoles()`.
  - `destroy(User $user)` — изтриване.

***

## Views
В `resources/views/users/`:
- `index.blade.php` — таблица с действия.
- `create.blade.php` — форма за създаване.
- `edit.blade.php` — форма за редакция.
- `_form.blade.php` — споделен фрагмент (име, email, telegram_chat_id, парола, роли).

***

## Routes
(Откъс от `routes/web.php`, защита с права `users.*`)

```php
Route::middleware(['auth', 'verified']).group(function () {
    Route::middleware('permission:users.view').group(function () {
        Route::get('users', [UserController::class, 'index']).name('users.index');
        Route::get('users/{user}', [UserController::class, 'show']).name('users.show');
    });
    Route::middleware('permission:users.create').group(function () {
        Route::get('users/create', [UserController::class, 'create']).name('users.create');
        Route::post('users', [UserController::class, 'store']).name('users.store');
    });
    Route::middleware('permission:users.edit').group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit']).name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update']).name('users.update');
    });
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        .middleware('permission:users.delete')
        .name('users.destroy');
});
```

***

## Functionality Overview
- Пълен CRUD за потребители.
- Присвояване на роли чрез Spatie `syncRoles()` (multi‑select).
- Валидация:
  - създаване: `name|required|max:255`, `email|required|email|unique:users`, `telegram_chat_id|required|unique:users`, `password|required|min:8`
  - редакция: уникалност на `email` и `telegram_chat_id` игнорира текущия `id`; паролата е опционална (ако е празна — не се променя).
- Хеширане на пароли: `Hash::make(...)`.
- Flash съобщения при успех.

***

## How It Works
1. **Index:** зарежда всички потребители → `users.index` с действия.
2. **Create:** зарежда роли и визуализира `_form`.
3. **Store:** валидира, хешира парола, създава `User`, `syncRoles()` при подадени роли, пренасочване към index.
4. **Edit:** зарежда потребител + роли, визуализира `_form` с избраните роли.
5. **Update:** валидира; ако паролата е празна → не се променя; обновява атрибутите; синхронизира ролите; пренасочва към index.
6. **Destroy:** изтрива потребителя; пренасочва с flash.

***

## Notes
- **Права:** `auth`, `verified`, и фини `permission:users.view|create|edit|delete`.
- **Роли/Политики:** само привилегировани потребители управляват роли; добавете Policies за забрана изтриване на себе си или суперадмин.
- **Telegram:** `telegram_chat_id` е уникален — полезен за Telegram нотификации.
- **Сигурност:** минимална дължина, хеширане, скрити полета; при промяна на email — опционална повторна верификация (`MustVerifyEmail`).
- **UX:** `_form` поддържа многократен избор на роли; полето за парола при редакция може да остане празно.
