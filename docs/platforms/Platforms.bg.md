# Platforms Module

---

## Description

Модулът **Platforms** управлява каталога на рекламните платформи (Telegram канали, YouTube, Facebook, сайтове). Осигурява CRUD, съхранява основни свойства (валута, часови пояс, активност) и поддържа връзки към прайс листи, слотове и резервации. При създаване изпраща известие до администратори и мениджъри.

---

## Models

-   `App\Models\Platform`
    **Връзки:**

    -   `hasMany PriceList`
    -   `hasMany Slot`
    -   `hasMany Booking`

    **Полета (`$fillable`):**

    -   `name`, `type` (`telegram|youtube|facebook|website`), `description`
    -   `currency` (една от `Platform::$currencies`: `USD|EUR|BGN`)
    -   `timezone` (от `DateTimeZone::listIdentifiers()`)
    -   `is_active` (boolean)

    **Статични данни:**

    -   `public static $currencies = ['USD'=>'US Dollar','EUR'=>'Euro','BGN'=>'Bulgarian Lev'];`

---

## Controllers

-   `App\Http\Controllers\PlatformController`
    -   `index(Request $request)` — списък с пагинация.
    -   `create()` — форма за създаване (подаване на списък с часови пояси).
    -   `store(Request $request)` — валидира, създава запис и изпраща `PlatformCreationNotification` до всички потребители с роли `admin` и `manager`.
    -   `show(Platform $platform)` — детайл, зарежда броя на `priceLists/slots/bookings` чрез `loadCount()`.
    -   `edit(Platform $platform)` — форма за редакция (включва списък с часови пояси).
    -   `update(Request $request, Platform $platform)` — валидиране и обновяване.
    -   `destroy(Platform $platform)` — изтриване.

---

## Views

В `resources/views/platforms/`:

-   `index.blade.php` — таблица с платформи, статуси и действия.
-   `create.blade.php` — форма за създаване (name/type/description/currency/timezone/is_active).
-   `edit.blade.php` — форма за редакция.

---

## Routes

(Пример от `routes/web.php` с права `platforms.*`)

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

-   CRUD на платформи (type/name/description/currency/timezone/is_active).
-   Пагинация на списъка (`latest('id')->paginate(15)->withQueryString()`).
-   Валутите идват от `Platform::$currencies`.
-   Часови пояси — от `DateTimeZone::listIdentifiers()`.
-   Свързани обекти: прайс листи, слотове, резервации
-   При създаване се изпраща `PlatformCreationNotification` към роли `admin` и `manager`.

**Валидация (според контролера):**

-   `name` — `required|string|max:255`
-   `type` — `required|in:telegram,youtube,facebook,website`
-   `description` — `nullable|string|max:1000`
-   `currency` — `required|string|in:USD,EUR,BGN`
-   `timezone` — `required|string|in:<всички идентификатори на DateTimeZone>`
-   `is_active` — `boolean`

---

## How It Works

1. Потребител с права отваря `platforms.index` и вижда таблица с активни/неактивни значки.
2. Създаване: `platforms.create` → попълване на форма → `platforms.store` валидира и записва.
3. След създаване контролерът взима текущия потребител (`auth()->user()`) и уведомява всички `admin` и `manager` чрез `notify(new PlatformCreationNotification($creator))`.
4. Преглед: `platforms.show` зарежда броя на свързаните обекти за бърза аналитика.
5. Редакция/Изтриване: `platforms.edit` / `platforms.update` и `platforms.destroy`.

---

## Notes

-   **Достъп:** маршрутите са защитени с `auth`, `verified`, `permission:platforms.*`.
-   **Референтни данни:** поддържайте валутите централно (както е), при нужда изнесете към конфигурация/БД.
-   **Форма:** във Blade ползвайте `old()` и стойности от `$platform`; за превключвателя използвайте `old('is_active', $platform->is_active ?? true)`.
-   **Индекси:** помислете за индекси върху `type`, `is_active`, евентуално `(type, is_active)`.
-   **Одит:** може да използвате Spatie Activity Log за проследяване на промени.
