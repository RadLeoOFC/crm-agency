# Модул Редове на поръчки

***

## Описание
Модулът **Редове на поръчки** управлява отделните услуги вътре в една поръчка. Всеки ред свързва услуга с поръчка и съхранява количество, единична цена и междинна сума.

***

## Модели
- `App\Models\OrderItem`
  - полета: `order_id`, `service_id`, `qty`, `price`, `subtotal`;
  - релации:
    - `belongsTo(Order::class)`;
    - `belongsTo(Service::class)`;
  - model events:
    - след `saved` — преизчислява общата сума на поръчката;
    - след `deleted` — преизчислява общата сума на поръчката.

***

## Контролери
- `App\Http\Controllers\OrderItemController`
  - `index(Order $order)` — списък с редовете за една поръчка.
  - `create(Order $order)` — форма за създаване с наличните услуги.
  - `store(Request $request, Order $order)` — валидира, изчислява междинната сума и създава реда.
  - `edit(Order $order, OrderItem $orderitem)` — форма за редакция.
  - `update(Request $request, Order $order, OrderItem $orderitem)` — валидира, преизчислява междинната сума и обновява реда.
  - `destroy(Order $order, OrderItem $orderitem)` — изтрива реда.
  - `show()` присъства в routes, но в момента е празен.

***

## Изгледи
Намират се в `resources/views/orderitems/`:
- `index.blade.php` — списък с редовете за избраната поръчка.
- `create.blade.php` — екран за създаване.
- `edit.blade.php` — екран за редакция.
- `_form.blade.php` — общ partial за формата.

***

## Маршрути
Дефинирани са в `routes/web.php` като вложен ресурс и с права `orderitems.*`:

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

## Какво прави модулът
- Добавя и управлява редовете в съществуваща поръчка.
- Валидира:
  - `service_id` — задължително и трябва да съществува в `services`;
  - `qty` — задължително цяло число, минимум `1`;
  - `price` — задължително число, минимум `0`.
- `subtotal` се изчислява в контролера като `round(qty * price)`.
- Общата сума на родителската поръчка се обновява автоматично чрез model events.

***

## Как работи
1. Потребителят отваря поръчка и влиза в списъка с нейните редове.
2. При create/edit избира услуга и въвежда количество и цена.
3. Контролерът изчислява `subtotal` и записва реда към текущата поръчка.
4. Събитието в `OrderItem` извиква `Order::countTotalAmount()`.
5. `total_amount` на поръчката става сумата от всички редове.

***

## Бележки
- **Права:** `orderitems.view|create|edit|delete`.
- **Вложен ресурс:** всеки ред винаги се управлява в контекста на конкретна поръчка.
- **Ценообразуване:** `subtotal` използва `round()` без отделна настройка за десетични знаци, затова в текущата реализация се закръгля до цяло.
- **Липсваща част:** `show()` има маршрут, но още не е имплементиран.
