# Clients Module

***

## Description
The **Clients** module is the customer directory for the agency. It lets you create, edit, deactivate, and delete client records, store contact and billing details (including VAT), and links clients to bookings and promo redemptions.

***

## Models
- `App\Models\Client`  
  **Relations:**
  - `hasMany Booking` — all client bookings
  - `hasMany PromoRedemption` — promo code redemptions
  
  **Fillable (`$fillable`):**
  - `name`, `contact_person`, `email`, `phone`, `company`
  - `vat_number`, `country`, `city`, `address`
  - `is_active` (boolean)

  **Casts (`$casts`):**
  - `is_active` → `boolean`

***

## Controllers
- `App\Http\Controllers\ClientController`
  - `index(Request $request)` — list clients.
  - `create()` — new client form.
  - `store(Request $request)` — validate and persist a client.
  - `edit(Client $client)` — edit form.
  - `update(Request $request, Client $client)` — validate and update.
  - `destroy(Client $client)` — delete client.

***

## Views
Located in `resources/views/clients/`:
- `index.blade.php` — table with actions
- `create.blade.php` — creation form
- `edit.blade.php` — edit form
- `_form.blade.php` — shared form partial

***

## Routes
(Example from `routes/web.php` with `clients.*` permissions)

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
- Maintain client profiles (name/contact, email, phone, company).
- Billing/registration details: `vat_number`, country, city, address.
- `is_active` flag to soft-disable without deleting.
- Relations to `bookings` and `promoRedemptions`.
- Server-side validation on create/update.

**Validation (from controller):**
- `name`, `contact_person` — `required|string|max:255`
- `email` — `required|string|max:1000` *(recommended: `email:rfc,dns|unique:clients,email`)*
- `phone` — `required|string|max:255` *(normalize to E.164 if possible)*
- `company` — `required|string|max:255`
- `vat_number` — `required|string|max:255` *(country-specific format check optional)*
- `country`, `city` — `required|string|max:100`
- `address` — `required|string|max:255`
- `is_active` — `boolean`

***

## How It Works
1. Authorized user opens `clients.index` to see the list.
2. Create flow: `clients.create` → `_form.blade.php` → `clients.store` validates and saves.
3. Edit flow: `clients.edit` → `clients.update` validates and updates fields.
4. Delete flow: `clients.destroy` (CSRF + `DELETE`) with confirmation.
5. Views display active/inactive status and action buttons; validation errors appear above the form.

***

## Notes
- **Access control:** routes protected by `auth`, `verified`, and `permission:clients.*`. For client self-access, apply Policies/scoped queries.
- **Data quality:** consider unique `email` and/or `phone`, phone normalization (E.164), and country-aware VAT validation.
- **Privacy/GDPR:** store only necessary data; add deletion/anonymization policy on request.
- **UX:** for large datasets add pagination, search/filters (by `company`, `status`, `country`). Add indexes on `is_active`, `company`, `email`.
- **Cross-links:** client detail can link to their `bookings` and `promoRedemptions` history.
