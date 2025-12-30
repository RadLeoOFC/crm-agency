# Clients Module

***

## Description
Модулът **Clients** е директорията с клиенти на агенцията. Позволява създаване, редактиране, деактивиране и изтриване на клиенти, съхранява контактни и фирмени данни (вкл. ДДС номер/VAT) и свързва клиентите с резервации и използвани промокодове.

***

## Models
- `App\Models\Client`  
  **Връзки:**
  - `hasMany Booking` — всички резервации на клиента
  - `hasMany PromoRedemption` — използвания на промокоди
  
  **Полета (`$fillable`):**
  - `name`, `contact_person`, `email`, `phone`, `company`
  - `vat_number`, `country`, `city`, `address`
  - `is_active` (boolean)

  **Кастове (`$casts`):**
  - `is_active` → `boolean`

***

## Controllers
- `App\Http\Controllers\ClientController`
  - `index(Request $request)` — списък с клиенти.
  - `create()` — форма за създаване.
  - `store(Request $request)` — валидиране и запис.
  - `edit(Client $client)` — форма за редакция.
  - `update(Request $request, Client $client)` — валидиране и обновяване.
  - `destroy(Client $client)` — изтриване.

***

## Views
В `resources/views/clients/`:
- `index.blade.php` — таблица с действия
- `create.blade.php` — форма за създаване
- `edit.blade.php` — форма за редакция
- `_form.blade.php` — общ шаблон за формата

***

## Routes
(Пример от `routes/web.php`, с права `clients.*`)

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
- Управление на клиентски профили (име/контактно лице, e‑mail, телефон, компания).
- Данни за фактуриране: `vat_number`, държава, град, адрес.
- Флаг `is_active` за временно изключване без изтриване.
- Връзки към `bookings` и `promoRedemptions`.
- Сървърна валидация при създаване/редакция.

**Валидация (по контролера):**
- `name`, `contact_person` — `required|string|max:255`
- `email` — `required|string|max:1000` *(препоръчително: `email:rfc,dns|unique:clients,email`)*
- `phone` — `required|string|max:255` *(нормализация към E.164)*
- `company` — `required|string|max:255`
- `vat_number` — `required|string|max:255` *(по избор: проверка по държава)*
- `country`, `city` — `required|string|max:100`
- `address` — `required|string|max:255`
- `is_active` — `boolean`

***

## How It Works
1. Потребител с права отваря `clients.index` и вижда списъка.
2. Създаване: `clients.create` → `_form.blade.php` → `clients.store` валидира и записва.
3. Редакция: `clients.edit` → `clients.update` валидира и обновява.
4. Изтриване: `clients.destroy` (CSRF + `DELETE`) след потвърждение.
5. Във view се показва активен/неактивен статус; грешките от валидацията се изписват над формата.

***

## Notes
- **Достъп:** `auth`, `verified`, `permission:clients.*`. За само-достъп на клиента — Policies/ограничени заявки.
- **Качество на данните:** препоръчва се уникалност на `email` и/или `phone`, нормализиране на телефон (E.164), проверка на `vat_number` по държава.
- **GDPR/Поверителност:** съхранявайте само необходимите данни; добавете политика за изтриване/анонимизиране при заявка.
- **UX:** при големи списъци — пагинация, търсене/филтри (по `company`, `status`, `country`). Индекси на `is_active`, `company`, `email`.
- **Връзки:** детайл на клиент може да води към неговите `bookings` и история на `promoRedemptions`.
