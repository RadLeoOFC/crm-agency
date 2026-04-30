# Orders Module

***

## Description
The **Orders** module manages the main sales records that connect a client, responsible manager, status, and calculated total amount. Orders act as parent documents for order items.

***

## Models
- `App\Models\Order`
  - fields: `client_id`, `manager_id`, `status`, `total_amount`;
  - relations:
    - `belongsTo(Client::class)`;
    - `belongsTo(User::class, 'manager_id')`;
    - `hasMany(OrderItem::class)`;
  - `countTotalAmount()` recalculates `total_amount` by summing related item subtotals.

***

## Controllers
- `App\Http\Controllers\OrderController`
  - `index()` — paginated list, eager loads `manager`, and limits records for non-admin/non-manager users.
  - `create()` — creation form with clients and users.
  - `store(Request $request)` — validates and creates an order.
  - `edit(Order $order)` — edit form with loaded client/manager.
  - `update(Request $request, Order $order)` — validates and updates the order.
  - `destroy(Order $order)` — deletes the order.
  - `show()` currently exists in routes, but the method body is empty.

***

## Views
Located in `resources/views/orders/`:
- `index.blade.php` — table with client, manager, status, total amount, and actions.
- `create.blade.php` — create screen.
- `edit.blade.php` — edit screen.
- `_form.blade.php` — shared form partial.

***

## Routes
Defined in `routes/web.php` with `auth`, `verified`, and `orders.*` permissions:

```php
Route::middleware('permission:orders.view')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware('permission:orders.create')->group(function () {
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
});

Route::middleware('permission:orders.edit')->group(function () {
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});

Route::delete('orders/{order}', [OrderController::class, 'destroy'])
    ->middleware('permission:orders.delete')
    ->name('orders.destroy');
```

***

## Functionality Overview
- Creates and manages parent order records.
- Validates:
  - `client_id` — required and must exist in `clients`;
  - `manager_id` — required and must exist in `users`;
  - `status` — required and one of `new`, `in_progress`, `completed`, `cancelled`.
- `total_amount` is maintained automatically from related order items.

***

## How It Works
1. User creates an order by choosing the client, manager, and status.
2. The order is stored without manual total calculation.
3. Users add order items under that order.
4. When order items are saved or deleted, the order model recalculates `total_amount`.
5. On the index page, non-admin/non-manager users only see their own records according to the current controller logic.

***

## Notes
- **Permissions:** `orders.view|create|edit|delete`.
- **Nested workflow:** order items live under `/orders/{order}/orderitems`.
- **Current code detail:** `index()` filters by `user_id`, while the model stores `manager_id`; if ownership filtering should use the manager field, that logic may need adjustment later.
- **Missing details:** `show()` is routed but not implemented yet.
