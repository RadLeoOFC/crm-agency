# Platforms Module

---

## Description

Модуль **Platforms** — каталог рекламных площадок (Telegram-каналы, YouTube, Facebook, сайты). Обеспечивает CRUD, хранение базовых свойств (валюта, часовой пояс, активность) и связи с прайс‑листами, слотами и бронированиями. При создании площадки рассылает уведомления администраторам и менеджерам.

---

## Models

-   `App\Models\Platform`
    **Связи:**

    -   `hasMany PriceList` — прайс‑листы площадки
    -   `hasMany Slot` — расписание/слоты
    -   `hasMany Booking` — бронирования на площадке

    **Заполняемые поля (`$fillable`):**

    -   `name`, `type` (`telegram|youtube|facebook|website`), `description`
    -   `currency` (одна из `Platform::$currencies`: `USD|EUR|BGN`)
    -   `timezone` (из `DateTimeZone::listIdentifiers()`)
    -   `is_active` (boolean)

    **Статические данные:**

    -   `public static $currencies = ['USD'=>'US Dollar','EUR'=>'Euro','BGN'=>'Bulgarian Lev'];`

---

## Controllers

-   `App\Http\Controllers\PlatformController`
    -   `index(Request $request)` — список площадок с пагинацией.
    -   `create()` — форма создания (передаёт список таймзон).
    -   `store(Request $request)` — валидация, создание и рассылка уведомлений ролям `admin` и `manager` через `PlatformCreationNotification`.
    -   `show(Platform $platform)` — страница детали, подгружает количества `priceLists/slots/bookings` через `loadCount()`.
    -   `edit(Platform $platform)` — форма редактирования (список таймзон).
    -   `update(Request $request, Platform $platform)` — валидация и обновление данных.
    -   `destroy(Platform $platform)` — удаление площадки.

---

## Views

Расположены в `resources/views/platforms/`:

-   `index.blade.php` — таблица площадок, метки статуса, действия.
-   `create.blade.php` — форма создания (name/type/description/currency/timezone/is_active).
-   `edit.blade.php` — форма редактирования.

---

## Routes

(Типовая конфигурация в `routes/web.php`, с правами `platforms.*`)

```php
Route::middleware(['auth','verified'])->group(function () {
    Route::middleware('permission:platforms.view')->group(function () {
        Route::get('platforms', [PlatformController::class, 'index'])->name('platforms.index');
        Route::get('platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');
    });

    Route::middleware('permission:platforms.create')->group(function () {
        Route::get('platforms/create', [PlatformController::class, 'create'])->name('platforms.create');
        Route::post('platforms', [PlatformController::class, 'store'])->name('platforms.store');
    });

    Route::middleware('permission:platforms.edit')->group(function () {
        Route::get('platforms/{platform}/edit', [PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update');
    });

    Route::delete('platforms/{platform}', [PlatformController::class, 'destroy'])
        ->middleware('permission:platforms.delete')
        ->name('platforms.destroy');
});
```

---

## Functionality Overview

-   CRUD площадок (тип/название/описание/валюта/таймзона/активность).
-   Пагинация списка (`latest('id')->paginate(15)->withQueryString()`).
-   Валюта — из статического списка `Platform::$currencies`.
-   Таймзона — из `DateTimeZone::listIdentifiers()`.
-   Связанные сущности: прайс‑листы, слоты, бронирования
-   Уведомления при создании — рассылка `PlatformCreationNotification` всем пользователям с ролями `admin` и `manager`.

**Валидация (из контроллера):**

-   `name` — `required|string|max:255`
-   `type` — `required|in:telegram,youtube,facebook,website`
-   `description` — `nullable|string|max:1000`
-   `currency` — `required|string|in:USD,EUR,BGN` (генерируется по ключам `Platform::$currencies`)
-   `timezone` — `required|string|in:<все значения из DateTimeZone>`
-   `is_active` — `boolean`

---

## How It Works

1. Пользователь с правами открывает список (`platforms.index`) → видит таблицу площадок с метками активности.
2. Создание: `platforms.create` → заполнение формы → `platforms.store` валидирует и создаёт запись.
3. После создания контроллер получает текущего пользователя (`auth()->user()`) и уведомляет всех `admin` и `manager` через `notify(new PlatformCreationNotification($creator))`.
4. Просмотр: `platforms.show` загружает счётчики связанных сущностей для быстрой аналитики.
5. Редактирование/удаление: `platforms.edit` / `platforms.update` и `platforms.destroy` соответственно.

---

## Notes

-   **Права доступа:** маршруты защищены `auth`, `verified`, `permission:platforms.*`.
-   **Справочники:** храните список валют централизованно (как сейчас) или вынесите в конфиг/БД при масштабировании.
-   **Данные формы:** в Blade проверяйте корректные ID полей, используйте `old()` и дефолты от `$platform`; для переключателя активности используйте `old('is_active', $platform->is_active ?? true)`.
-   **Индексация:** стоит добавить индексы на `type`, `is_active`, а также составной индекс `(type, is_active)` при поисках.
-   **Аудит:** при необходимости — Spatie Activity Log для отслеживания изменений площадок.
