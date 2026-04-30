# Order Items Module

***

## Description
The **Order Items** module manages the individual service lines inside an order. Each item links one service to one order and stores quantity, unit price, and subtotal.

***

## Models
- `App\Models\OrderItem`
  - fields: `order_id`, `service_id`, `qty`, `price`, `subtotal`;
  - relations:
    - `belongsTo(Order::class)`;
    - `belongsTo(Service::class)`;
  - model events:
    - after `saved` — recalculates the parent order total;
    - after `deleted` — recalculates the parent order total.

***

## Controllers
- `App\Http\Controllers\OrderItemController`
  - `index(Order $order)` — lists all items for one order.
  - `create(Order $order)` — creation form with available services.
  - `store(Request $request, Order $order)` — validates, calculates subtotal, creates a nested item.
  - `edit(Order $order, OrderItem $orderitem)` — edit form.
  - `update(Request $request, Order $order, OrderItem $orderitem)` — validates, recalculates subtotal, updates item.
  - `destroy(Order $order, OrderItem $orderitem)` — deletes item.
  - `show()` currently exists in routes, but the method body is empty.

***

## Views
Located in `resources/views/orderitems/`:
- `index.blade.php` — list of order lines for a selected order.
- `create.blade.php` — create screen.
- `edit.blade.php` — edit screen.
- `_form.blade.php` — shared form partial.

***

## Routes
Defined in `routes/web.php` with nested order context and `orderitems.*` permissions:

```php
Route::middleware('permission:orderitems.view')->group(function () {
    Route::get('orders/{order}/orderitems', [OrderItemController::class, 'index'])->name('orderitems.index');
    Route::get('orders/{order}/orderitems/{orderitem}', [OrderItemController::class, 'show'])->name('orderitems.show');
});

Route::middleware('permission:orderitems.create')->group(function () {
    Route::get('orders/{order}/orderitems/create', [OrderItemController::class, 'create'])->name('orderitems.create');
    Route::post('orders/{order}/orderitems', [OrderItemController::class, 'store'])->name('orderitems.store');
});

Route::middleware('permission:orderitems.edit')->group(function () {
    Route::get('orders/{order}/orderitems/{orderitem}/edit', [OrderItemController::class, 'edit'])->name('orderitems.edit');
    Route::put('orders/{order}/orderitems/{orderitem}', [OrderItemController::class, 'update'])->name('orderitems.update');
});

Route::delete('orders/{order}/orderitems/{orderitem}', [OrderItemController::class, 'destroy'])
    ->middleware('permission:orderitems.delete')
    ->name('orderitems.destroy');
```

***

## Functionality Overview
- Adds and manages line items inside an existing order.
- Validates:
  - `service_id` — required and must exist in `services`;
  - `qty` — required integer, minimum `1`;
  - `price` — required numeric, minimum `0`.
- `subtotal` is calculated in the controller as `round(qty * price)`.
- Parent order totals are recalculated automatically via model events.

***

## How It Works
1. User opens an order and navigates to its items list.
2. On create/edit, the user selects a service and enters quantity and price.
3. Controller calculates `subtotal` and saves the record under the current order.
4. The `OrderItem` model event triggers `Order::countTotalAmount()`.
5. The order's `total_amount` becomes the sum of all related item subtotals.

***

## Notes
- **Permissions:** `orderitems.view|create|edit|delete`.
- **Nested resource:** every item is always managed in the context of an order.
- **Pricing behavior:** subtotal uses `round()` without decimal precision configuration, so amounts are rounded to whole units in the current implementation.
- **Missing details:** `show()` is routed but not implemented yet.
