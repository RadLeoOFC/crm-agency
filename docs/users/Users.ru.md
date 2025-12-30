# Users Module

***

## Description
Модуль **Users** управляет учётными записями сотрудников и партнёров CRM: создание, редактирование, назначение ролей (через Spatie Permissions), удаление. Пароли хэшируются, доступ регулируется правами `users.*`.

***

## Models
- `App\Models\User`
  - Наследует `Authenticatable`, трейт `HasRoles` (Spatie) и `Notifiable`.
  - **$fillable:** `name`, `email`, `telegram_chat_id`, `password`
  - **$hidden:** `password`, `remember_token`
  - **casts():** `email_verified_at: datetime`, `password: hashed`

***

## Controllers
- `App\Http\Controllers\UserController`
  - `index()` — список пользователей.
  - `show(User $user)` — просмотр карточки пользователя (если используется).
  - `create()` — форма создания + загрузка всех ролей.
  - `store(Request $request)` — валидация, хеширование пароля, создание, синхронизация ролей.
  - `edit(User $user)` — форма редактирования + роли.
  - `update(Request $request, User $user)` — валидация, условная смена пароля, синхронизация ролей.
  - `destroy(User $user)` — удаление пользователя.

***

## Views
В `resources/views/users/`:
- `index.blade.php` — таблица пользователей, кнопки редактирования/удаления.
- `create.blade.php` — форма создания.
- `edit.blade.php` — форма редактирования.
- `_form.blade.php` — общий фрагмент формы (имя, email, telegram_chat_id, пароль, роли).

***

## Routes
(Фрагмент из `routes/web.php`, защищено правами `users.*`)

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
- CRUD пользователей.
- Назначение ролей (множественный выбор) через Spatie `syncRoles()`.
- Валидация:
  - при создании: `name|required|max:255`, `email|required|email|unique:users`, `telegram_chat_id|required|unique:users`, `password|required|min:8`
  - при обновлении: уникальность `email` и `telegram_chat_id` игнорирует текущий `id`, `password` — опционален (если пусто — не меняем).
- Хеширование пароля: `Hash::make(...)`.
- Flash‑сообщения об успехе.

***

## How It Works
1. **Index:** `User::all()` → `users.index` с таблицей и действиями.
2. **Create:** грузим список ролей, рендерим `_form`.
3. **Store:** валидируем, хешируем пароль, создаём `User`, `syncRoles()` если переданы выбранные роли, редирект на index.
4. **Edit:** грузим пользователя и роли, рендерим `_form` (подсвечиваем текущие роли).
5. **Update:** валидируем; если поле `password` пустое — не изменяем; обновляем атрибуты, синхронизируем роли; редирект на index.
6. **Destroy:** удаляем пользователя, редирект с flash.

***

## Notes
- **Права:** детальный доступ через `permission:users.view|create|edit|delete`. Сам модуль за `auth`+`verified`.
- **Роли и политики:** назначение ролей — только для пользователей с соответствующими правами; при необходимости детализируйте через Policies (например, запрет удалять самого себя или супер‑админа).
- **Telegram:** `telegram_chat_id` уникален — пригодится для нотификаций в Telegram.
- **Безопасность:** минимальная длина пароля, хеширование, скрытие пароля в JSON; при смене email — возможна повторная верификация (если включить MustVerifyEmail).
- **UX:** в `_form` предусмотрен множественный выбор ролей; поле пароля при редактировании можно оставить пустым, чтобы не менять его.
