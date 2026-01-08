# Clients Module

***

## Description
Модуль **Clients** — справочник клиентов рекламного агентства. Позволяет создавать, редактировать и деактивировать карточки клиентов, хранить контактные данные и реквизиты (включая VAT), а также связывает клиентов с бронированиями и использованием промокодов.

***

## Models
- `App\Models\Client`  
  **Связи:**
  - `hasMany Booking` — все бронирования клиента
  - `hasMany PromoRedemption` — использования промокодов
  
  **Заполняемые поля (`$fillable`):**
  - `name`, `contact_person`, `email`, `phone`, `company`
  - `vat_number`, `country`, `city`, `address`
  - `is_active` (boolean)

  **Касты (`$casts`):**
  - `is_active` → `boolean`

***

## Controllers
- `App\Http\Controllers\ClientController`
  - `index(Request $request)` — список клиентов.
  - `create()` — форма создания клиента.
  - `store(Request $request)` — валидация и сохранение нового клиента.
  - `edit(Client $client)` — форма редактирования.
  - `update(Request $request, Client $client)` — валидация и обновление данных.
  - `destroy(Client $client)` — удаление клиента.

***

## Views
Расположены в `resources/views/clients/`:
- `index.blade.php` — таблица клиентов с действиями
- `create.blade.php` — форма создания
- `edit.blade.php` — форма редактирования
- `_form.blade.php` — общий фрагмент формы

***

## Routes
(Пример конфигурации в `routes/web.php`, с пермишенами `clients.*`)

```php
Route::middleware(['auth','verified'])->group(function () {
    Route::middleware('permission:clients.view')->group(function () {
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    });

    Route::middleware('permission:clients.create')->group(function () {
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    });

    Route::middleware('permission:clients.edit')->group(function () {
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    });

    Route::delete('clients/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients.delete')
        ->name('clients.destroy');
});
```

***

## Functionality Overview
- Ведение карточки клиента (ФИО/контактное лицо, e‑mail, телефон, компания).
- Реквизиты: `vat_number`, страна, город, адрес.
- Флаг активности `is_active` для мягкого отключения без удаления.
- Связь с бронированиями (`bookings`) и списаниями промокодов (`promoRedemptions`).
- Серверная валидация полей при создании/редактировании.

**Валидация (по коду контроллера):**
- `name`, `contact_person` — `required|string|max:255`
- `email` — `required|string|max:1000` *(рекомендуется усилить до `email:rfc,dns|unique:clients,email`)*
- `phone` — `required|string|max:255` *(рекомендуется маска/нормализация)*
- `company` — `required|string|max:255`
- `vat_number` — `required|string|max:255` *(опционально: валидация формата страны)*
- `country`, `city` — `required|string|max:100`
- `address` — `required|string|max:255`
- `is_active` — `boolean`

***

## How It Works
1. Пользователь с нужными правами открывает `clients.index` и видит таблицу клиентов.
2. Для добавления — `clients.create` → форма `_form.blade.php` → `clients.store` выполняет валидацию и создаёт запись.
3. Редактирование — `clients.edit` → `clients.update` валидирует и обновляет поля.
4. Удаление — `clients.destroy` (CSRF + метод `DELETE`), после подтверждения.
5. В представлениях выводится статус активности и кнопки действий; ошибки валидации отображаются сверху формы.

***

## Notes
- **Доступы:** маршруты защищены `auth`, `verified` и `permission:clients.*`. Для чтения клиентом собственных данных используйте Policies/Scoped queries.
- **Качество данных:** рекомендуется добавить уникальность `email` и/или `phone`, нормализовать телефон (E.164) и валидировать `vat_number` по стране.
- **GDPR/Privacy:** храните только необходимые поля, добавьте политику удаления/анонимизации по запросу.
- **UX:** при массовых списках — пагинация, поиск/фильтры (по `company`, `status`, `country`). Индексы по `is_active`, `company`, `email`.
- **Связанные разделы:** карточка клиента может вести на список его `bookings` и историю `promoRedemptions`.
