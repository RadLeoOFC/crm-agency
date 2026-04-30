# Модуль Услуги

***

## Описание
Модуль **Услуги** хранит продаваемые позиции каталога агентства. У каждой услуги есть код, имя/категория, описание, базовая цена, валюта и флаг активности, а затем она может использоваться в позициях заказа.

***

## Модели
- `App\Models\Service`
  - поля: `code`, `name`, `description`, `base_price`, `currency`, `is_active`;
  - `$fillable` разрешает массовое заполнение этих полей;
  - связь: `hasMany(OrderItem::class)`;
  - статический список `Service::$currencies` ограничивает валюты: `USD`, `EUR`, `BGN`, `GBP`, `RUB`.

***

## Контроллеры
- `App\Http\Controllers\ServiceController`
  - `index()` — список услуг с пагинацией.
  - `create()` — форма создания.
  - `store(Request $request)` — валидирует и создает услугу.
  - `edit(Service $service)` — форма редактирования.
  - `update(Request $request, Service $service)` — валидирует и обновляет услугу.
  - `destroy(Service $service)` — удаляет услугу.
  - `show()` есть в маршрутах, но тело метода пока пустое.

***

## Представления
Находятся в `resources/views/services/`:
- `index.blade.php` — таблица с кодом, именем, описанием, ценой, активностью и действиями.
- `create.blade.php` — экран создания.
- `edit.blade.php` — экран редактирования.
- `_form.blade.php` — общий partial формы.

***

## Маршруты
Определены в `routes/web.php` с `auth`, `verified` и правами `services.*`:

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

## Что делает модуль
- Поддерживает переиспользуемый каталог услуг для будущих заказов.
- Валидирует:
  - `code` — обязательная строка до 64 символов;
  - `name` — обязательно; при создании ограничено значениями `SEO`, `SMM`, `PPC`, `Dev`;
  - `description` — обязательная строка до 1000 символов;
  - `base_price` — число и минимум `0`;
  - `currency` — одна из `Service::$currencies`;
  - `is_active` — boolean.
- После create/update/delete возвращает flash-сообщение об успехе.

***

## Как работает
1. Пользователь открывает список услуг и видит каталог с пагинацией.
2. На create/edit заполняет код, имя, описание, базовую цену, валюту и активность.
3. Контроллер валидирует запрос и записывает строку в `services`.
4. Затем позиции заказа используют услугу через `service_id`.

***

## Примечания
- **Права:** `services.view|create|edit|delete`.
- **Переиспользование:** услуги работают как шаблоны; фактическая цена в заказе хранится отдельно в позиции заказа.
- **Текущее поведение:** `store()` ограничивает `name` четырьмя значениями, а `update()` уже позволяет любую строку до 64 символов.
- **Недостающая часть:** `show()` имеет маршрут, но пока не реализован.
