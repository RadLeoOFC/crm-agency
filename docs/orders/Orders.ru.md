# Модуль Заказы

***

## Описание
Модуль **Заказы** управляет основными коммерческими записями, которые связывают клиента, ответственного менеджера, статус и рассчитанную общую сумму. Заказы выступают родительскими документами для позиций заказа.

***

## Модели
- `App\Models\Order`
  - поля: `client_id`, `manager_id`, `status`, `total_amount`;
  - связи:
    - `belongsTo(Client::class)`;
    - `belongsTo(User::class, 'manager_id')`;
    - `hasMany(OrderItem::class)`;
  - `countTotalAmount()` пересчитывает `total_amount` как сумму промежуточных сумм по позициям.

***

## Контроллеры
- `App\Http\Controllers\OrderController`
  - `index()` — список с пагинацией, eager loading `manager` и ограничением для non-admin/non-manager пользователей.
  - `create()` — форма создания с клиентами и пользователями.
  - `store(Request $request)` — валидирует и создает заказ.
  - `edit(Order $order)` — форма редактирования с загруженными клиентом и менеджером.
  - `update(Request $request, Order $order)` — валидирует и обновляет заказ.
  - `destroy(Order $order)` — удаляет заказ.
  - `show()` есть в маршрутах, но тело метода пока пустое.

***

## Представления
Находятся в `resources/views/orders/`:
- `index.blade.php` — таблица с клиентом, менеджером, статусом, общей суммой и действиями.
- `create.blade.php` — экран создания.
- `edit.blade.php` — экран редактирования.
- `_form.blade.php` — общий partial формы.

***

## Маршруты
Определены в `routes/web.php` с `auth`, `verified` и правами `orders.*`:

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

## Что делает модуль
- Создает и ведет родительские записи заказов.
- Валидирует:
  - `client_id` — обязательно и должно существовать в `clients`;
  - `manager_id` — обязательно и должно существовать в `users`;
  - `status` — обязательно и одно из `new`, `in_progress`, `completed`, `cancelled`.
- `total_amount` поддерживается автоматически на основе связанных позиций.

***

## Как работает
1. Пользователь создает заказ, выбирая клиента, менеджера и статус.
2. Заказ сохраняется без ручного расчета общей суммы.
3. Затем в заказ добавляются позиции.
4. Когда позиция сохраняется или удаляется, модель заказа пересчитывает `total_amount`.
5. В списке пользователи без ролей admin/manager видят только свои записи согласно текущей логике контроллера.

***

## Примечания
- **Права:** `orders.view|create|edit|delete`.
- **Вложенный процесс:** позиции находятся по пути `/orders/{order}/orderitems`.
- **Текущий код:** `index()` фильтрует по `user_id`, а модель хранит `manager_id`; если владение должно определяться менеджером, позже может понадобиться корректировка.
- **Недостающая часть:** `show()` имеет маршрут, но пока не реализован.
