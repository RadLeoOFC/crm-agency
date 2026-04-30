# Services Module

***

## Description
The **Services** module stores the agency's sellable catalog items. Each service has a code, name/category, description, base price, currency, and active flag, and can later be reused in order items.

***

## Models
- `App\Models\Service`
  - fields: `code`, `name`, `description`, `base_price`, `currency`, `is_active`;
  - `$fillable` allows mass assignment for those fields;
  - relation: `hasMany(OrderItem::class)`;
  - static list `Service::$currencies` limits allowed currencies (`USD`, `EUR`, `BGN`, `GBP`, `RUB`).

***

## Controllers
- `App\Http\Controllers\ServiceController`
  - `index()` — paginated list of services.
  - `create()` — creation form.
  - `store(Request $request)` — validates input and creates a service.
  - `edit(Service $service)` — edit form.
  - `update(Request $request, Service $service)` — validates and updates the service.
  - `destroy(Service $service)` — deletes the service.
  - `show()` currently exists in routes, but the method body is empty.

***

## Views
Located in `resources/views/services/`:
- `index.blade.php` — table with code, name, description, price, active flag, and actions.
- `create.blade.php` — create screen.
- `edit.blade.php` — edit screen.
- `_form.blade.php` — shared form partial.

***

## Routes
Defined in `routes/web.php` with `auth`, `verified`, and `services.*` permissions:

```php
Route::middleware('permission:services.view')->group(function () {
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');
});

Route::middleware('permission:services.create')->group(function () {
    Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
});

Route::middleware('permission:services.edit')->group(function () {
    Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
});

Route::delete('services/{service}', [ServiceController::class, 'destroy'])
    ->middleware('permission:services.delete')
    ->name('services.destroy');
```

***

## Functionality Overview
- Keeps a reusable services catalog for later order composition.
- Validates:
  - `code` — required string up to 64 chars;
  - `name` — required; on create limited to `SEO`, `SMM`, `PPC`, `Dev`;
  - `description` — required string up to 1000 chars;
  - `base_price` — numeric and non-negative;
  - `currency` — must be one of `Service::$currencies`;
  - `is_active` — boolean.
- Uses flash success messages after create, update, and delete.

***

## How It Works
1. User opens the services index and sees paginated records.
2. On create/edit, the form collects code, name, description, base price, currency, and active status.
3. The controller validates the request and writes the record to `services`.
4. Order items later reference services through `service_id`.

***

## Notes
- **Permissions:** `services.view|create|edit|delete`.
- **Catalog reuse:** services act as templates; actual order item price is stored separately in the order item.
- **Current code behavior:** `store()` restricts `name` to four values, while `update()` allows any string up to 64 chars.
- **Missing details:** `show()` is routed but not implemented, so direct service detail pages are not ready yet.
