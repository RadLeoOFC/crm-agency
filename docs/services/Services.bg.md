# Модул Услуги

***

## Описание
Модулът **Услуги** съхранява продаваемите позиции в каталога на агенцията. Всяка услуга има код, име/категория, описание, базова цена, валута и активен статус и после може да се използва в редове на поръчки.

***

## Модели
- `App\Models\Service`
  - полета: `code`, `name`, `description`, `base_price`, `currency`, `is_active`;
  - `$fillable` позволява масово записване на тези полета;
  - релация: `hasMany(OrderItem::class)`;
  - статичният списък `Service::$currencies` ограничава валутите до `USD`, `EUR`, `BGN`, `GBP`, `RUB`.

***

## Контролери
- `App\Http\Controllers\ServiceController`
  - `index()` — страница със списък и пагинация.
  - `create()` — форма за създаване.
  - `store(Request $request)` — валидира и създава услуга.
  - `edit(Service $service)` — форма за редакция.
  - `update(Request $request, Service $service)` — валидира и обновява услугата.
  - `destroy(Service $service)` — изтрива услугата.
  - `show()` присъства в routes, но в момента е празен.

***

## Изгледи
Намират се в `resources/views/services/`:
- `index.blade.php` — таблица с код, име, описание, цена, активност и действия.
- `create.blade.php` — екран за създаване.
- `edit.blade.php` — екран за редакция.
- `_form.blade.php` — общ partial за формата.

***

## Маршрути
Дефинирани са в `routes/web.php` с `auth`, `verified` и права `services.*`:

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

## Какво прави модулът
- Поддържа преизползваем каталог от услуги за бъдещи поръчки.
- Валидира:
  - `code` — задължителен низ до 64 символа;
  - `name` — задължително; при създаване е ограничено до `SEO`, `SMM`, `PPC`, `Dev`;
  - `description` — задължителен низ до 1000 символа;
  - `base_price` — число и минимум `0`;
  - `currency` — трябва да е една от `Service::$currencies`;
  - `is_active` — boolean.
- След create/update/delete връща success flash съобщение.

***

## Как работи
1. Потребителят отваря списъка с услуги и вижда странициран каталог.
2. При create/edit попълва код, име, описание, базова цена, валута и активност.
3. Контролерът валидира заявката и записва ред в `services`.
4. После редовете на поръчки използват услугата чрез `service_id`.

***

## Бележки
- **Права:** `services.view|create|edit|delete`.
- **Преизползване:** услугите играят роля на шаблон; реалната цена в поръчката се пази отделно в реда.
- **Текущо поведение:** `store()` ограничава `name` до четири стойности, а `update()` позволява произволен низ до 64 символа.
- **Липсваща част:** `show()` има маршрут, но още не е имплементиран.
