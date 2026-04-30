# Модуль Позиции заказа

***

## Описание
Модуль **Позиции заказа** управляет отдельными строками услуг внутри заказа. Каждая позиция связывает услугу с заказом и хранит количество, цену за единицу и промежуточную сумму.

***

## Модели
- `App\Models\OrderItem`
  - поля: `order_id`, `service_id`, `qty`, `price`, `subtotal`;
  - связи:
    - `belongsTo(Order::class)`;
    - `belongsTo(Service::class)`;
  - model events:
    - после `saved` — пересчитывает общую сумму заказа;
    - после `deleted` — пересчитывает общую сумму заказа.

***

## Контроллеры
- `App\Http\Controllers\OrderItemController`
  - `index(Order $order)` — список всех позиций одного заказа.
  - `create(Order $order)` — форма создания с доступными услугами.
  - `store(Request $request, Order $order)` — валидирует, считает промежуточную сумму и создает вложенную позицию.
  - `edit(Order $order, OrderItem $orderitem)` — форма редактирования.
  - `update(Request $request, Order $order, OrderItem $orderitem)` — валидирует, пересчитывает промежуточную сумму и обновляет позицию.
  - `destroy(Order $order, OrderItem $orderitem)` — удаляет позицию.
  - `show()` есть в маршрутах, но тело метода пока пустое.

***

## Представления
Находятся в `resources/views/orderitems/`:
- `index.blade.php` — список строк для выбранного заказа.
- `create.blade.php` — экран создания.
- `edit.blade.php` — экран редактирования.
- `_form.blade.php` — общий partial формы.

***

## Маршруты
Определены в `routes/web.php` как вложенный ресурс и с правами `orderitems.*`:

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

## Что делает модуль
- Добавляет и управляет строками внутри существующего заказа.
- Валидирует:
  - `service_id` — обязательно и должно существовать в `services`;
  - `qty` — обязательное целое число, минимум `1`;
  - `price` — обязательное число, минимум `0`.
- `subtotal` рассчитывается в контроллере как `round(qty * price)`.
- Общая сумма родительского заказа обновляется автоматически через model events.

***

## Как работает
1. Пользователь открывает заказ и переходит к списку его позиций.
2. На create/edit выбирает услугу и вводит количество и цену.
3. Контроллер рассчитывает `subtotal` и сохраняет строку в текущем заказе.
4. Событие в `OrderItem` вызывает `Order::countTotalAmount()`.
5. `total_amount` заказа становится суммой всех связанных позиций.

***

## Примечания
- **Права:** `orderitems.view|create|edit|delete`.
- **Вложенный ресурс:** каждая позиция всегда управляется в контексте конкретного заказа.
- **Поведение цен:** `subtotal` использует `round()` без отдельной настройки точности, поэтому в текущей реализации суммы округляются до целых.
- **Недостающая часть:** `show()` имеет маршрут, но пока не реализован.
