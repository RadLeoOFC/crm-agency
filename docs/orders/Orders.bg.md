# Модул Поръчки

***

## Описание
Модулът **Поръчки** управлява основните търговски записи, които свързват клиент, отговорен мениджър, статус и изчислена обща сума. Поръчките са родителските документи за редовете на поръчки.

***

## Модели
- `App\Models\Order`
  - полета: `client_id`, `manager_id`, `status`, `total_amount`;
  - релации:
    - `belongsTo(Client::class)`;
    - `belongsTo(User::class, 'manager_id')`;
    - `hasMany(OrderItem::class)`;
  - `countTotalAmount()` преизчислява `total_amount` като сума от междинните суми на редовете.

***

## Контролери
- `App\Http\Controllers\OrderController`
  - `index()` — списък с пагинация, eager load на `manager` и ограничение за не-admin/non-manager потребители.
  - `create()` — форма с клиенти и потребители.
  - `store(Request $request)` — валидира и създава поръчка.
  - `edit(Order $order)` — форма за редакция с заредени клиент и мениджър.
  - `update(Request $request, Order $order)` — валидира и обновява поръчката.
  - `destroy(Order $order)` — изтрива поръчката.
  - `show()` присъства в routes, но в момента е празен.

***

## Изгледи
Намират се в `resources/views/orders/`:
- `index.blade.php` — таблица с клиент, мениджър, статус, обща сума и действия.
- `create.blade.php` — екран за създаване.
- `edit.blade.php` — екран за редакция.
- `_form.blade.php` — общ partial за формата.

***

## Маршрути
Дефинирани са в `routes/web.php` с `auth`, `verified` и права `orders.*`:

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

## Какво прави модулът
- Създава и управлява родителските записи на поръчките.
- Валидира:
  - `client_id` — задължително и трябва да съществува в `clients`;
  - `manager_id` — задължително и трябва да съществува в `users`;
  - `status` — задължително и едно от `new`, `in_progress`, `completed`, `cancelled`.
- `total_amount` се поддържа автоматично от свързаните редове.

***

## Как работи
1. Потребителят създава поръчка, като избира клиент, мениджър и статус.
2. Поръчката се записва без ръчно изчисление на общата сума.
3. След това към нея се добавят редове на поръчката.
4. Когато ред се запише или изтрие, моделът на поръчката преизчислява `total_amount`.
5. В списъка потребители без роли admin/manager виждат само свои записи според текущата логика в контролера.

***

## Бележки
- **Права:** `orders.view|create|edit|delete`.
- **Вложен процес:** редовете са под `/orders/{order}/orderitems`.
- **Текущ код:** `index()` филтрира по `user_id`, а моделът използва `manager_id`; ако собствеността трябва да е по мениджър, по-късно може да е нужна корекция.
- **Липсваща част:** `show()` има маршрут, но още не е имплементиран.
