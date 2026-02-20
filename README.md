# Adv CRM Project

Internal CRM for a digital ad agency: management of platforms, price lists, slots/schedules, bookings, promo codes, clients, and roles.

---

## Installation

```
git clone https://github.com/RadLeoOFC/crm-agency.git
cd crm-agency
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---
