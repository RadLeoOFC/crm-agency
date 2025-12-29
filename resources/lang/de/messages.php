<?php

return [
    'welcome_user_title' => 'Willkommen im Verwaltungssystem für Werbeflächen!',
    'welcome_user_description' => 'Reichen Sie Werbeanfragen ein und wir platzieren Ihre Werbung auf unseren Plattformen.',
    'go_to_dashboard' => 'Zum Dashboard',
    'register' => 'Registrieren',
    'login' => 'Anmelden',
    'code' => 'Code',
    'name' => 'Name',
    'edit' => 'Bearbeiten',
    'delete' => 'Löschen',
    'languages' => 'Sprachen',
    'add_language' => 'Sprache hinzufügen',
    'language_added' => 'Sprache erfolgreich hinzugefügt',

    /*
    |--------------------------------------------------------------------------
    | Platforms
    |--------------------------------------------------------------------------
    */
    'platforms' => [
        'title' => 'Plattformen',
        'add' => 'Plattform hinzufügen',
        'edit' => 'Plattform bearbeiten',
        'create' => 'Plattform erstellen',
        'update' => 'Plattform aktualisieren',
        'back' => 'Zurück zur Liste',

        'fields' => [
            'name' => 'Plattformname',
            'type' => 'Plattformtyp',
            'description' => 'Beschreibung',
            'currency' => 'Währung',
            'timezone' => 'Zeitzone',
            'status' => 'Status',
            'active' => 'Aktiv',
            'inactive' => 'Inaktiv',
        ],

        'actions' => [
            'actions' => 'Aktionen',
            'edit' => 'Bearbeiten',
            'delete' => 'Löschen',
            'confirm_delete' => 'Möchten Sie diese Plattform wirklich löschen?',
        ],

        'types' => [
            'telegram' => 'Telegram',
            'youtube' => 'YouTube',
            'facebook' => 'Facebook',
            'website' => 'Webseite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Lists
    |--------------------------------------------------------------------------
    */
    'pricelists' => [
        'title' => 'Preislisten',

        'fields' => [
            'platform' => 'Plattform',
            'name' => 'Name',
            'currency' => 'Währung',
            'period' => 'Zeitraum',
            'timezone' => 'Zeitzone',
            'active' => 'Aktiv',
            'slot_duration' => 'Slot-Dauer (Min)',
            'valid_from' => 'Gültig ab',
            'valid_to' => 'Gültig bis',
        ],

        'links' => [
            'rules' => 'Regeln',
            'overrides' => 'Ausnahmen',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    */
    'clients' => [
        'title' => 'Kunden',
        'add' => 'Kunden hinzufügen',

        'fields' => [
            'name' => 'Name',
            'contact_person' => 'Ansprechpartner',
            'email' => 'E-Mail',
            'phone' => 'Telefon',
            'company' => 'Firma',
            'vat' => 'USt-IdNr.',
            'country' => 'Land',
            'city' => 'Stadt',
            'address' => 'Adresse',
            'active' => 'Aktiv',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Promocodes
    |--------------------------------------------------------------------------
    */
    'promocodes' => [
        'title' => 'Promocodes',

        'fields' => [
            'code' => 'Code',
            'type' => 'Typ',
            'value' => 'Wert',
            'window' => 'Fenster',
            'applies_to' => 'Gilt für',
            'active' => 'Aktiv',

            'discount_type' => 'Rabattart',
            'discount_value' => 'Rabattwert',
            'currency' => 'Währung',
            'starts_at' => 'Startdatum',
            'ends_at' => 'Enddatum',
            'min_order' => 'Mindestbestellwert',
            'limit_per_client' => 'Limit pro Kunde',
            'limit_total' => 'Gesamtlimit',
            'stackable' => 'Kombinierbar',
        ],

        'types' => [
            'percent' => 'Prozent',
            'fixed' => 'Fester Betrag',
        ],

        'applies' => [
            'global' => 'Global',
            'platform' => 'Plattform',
            'price_list' => 'Preisliste',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slots
    |--------------------------------------------------------------------------
    */
    'slots' => [
        'title' => 'Slots',

        'fields' => [
            'weekday' => 'Wochentag',
            'start' => 'Beginn',
            'end' => 'Ende',
            'price' => 'Slot-Preis',
            'capacity' => 'Kapazität',
            'status' => 'Status',
            'platform' => 'Plattform',
        ],

        'statuses' => [
            'available' => 'Verfügbar',
            'reserved' => 'Reserviert',
            'booked' => 'Gebucht',
            'cancelled' => 'Storniert',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */
    'bookings' => [
        'title' => 'Buchungen',
        'add' => 'Neue Buchung',
        'edit' => 'Bearbeiten',
        'view' => 'Anzeigen',
        'save' => 'Speichern',
        'cancel' => 'Abbrechen',
        'no_items' => 'Keine Buchungen vorhanden',
        'delete_confirm' => 'Buchung löschen?',

        'fields' => [
            'platform' => 'Plattform',
            'client' => 'Kunde',
            'period' => 'Zeitraum',
            'price' => 'Preis',
            'status' => 'Status',
            'starts_at' => 'Startdatum',
            'ends_at' => 'Enddatum',
            'promo_code' => 'Promocode',
            'notes' => 'Notizen',
        ],

        'statuses' => [
            'pending' => 'Ausstehend',
            'confirmed' => 'Bestätigt',
            'cancelled' => 'Storniert',
            'completed' => 'Abgeschlossen',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    'users' => [
        'title' => 'Benutzer',
        'add' => 'Neuer Benutzer',
        'edit' => 'Bearbeiten',
        'delete_confirm' => 'Möchten Sie diesen Benutzer wirklich löschen?',

        'fields' => [
            'name' => 'Name',
            'email' => 'E-Mail',
            'telegram_chat_id' => 'Telegram Chat ID',
            'password' => 'Passwort',
            'roles' => 'Rollen',
        ],

        'password_hint' => 'Leer lassen, wenn keine Änderung gewünscht',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'title' => 'Rollen',
        'list' => 'Rollenliste',
        'add' => 'Rolle hinzufügen',
        'edit' => 'Bearbeiten',
        'create' => 'Rolle erstellen',
        'update' => 'Aktualisieren',
        'back' => 'Zurück',
        'delete_confirm' => 'Möchten Sie diese Rolle wirklich löschen?',

        'fields' => [
            'name' => 'Rollenname',
            'permissions' => 'Berechtigungen',
            'actions' => 'Aktionen',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Rules
    |--------------------------------------------------------------------------
    */
    'pricerules' => [
        'title' => 'Regeln',
        'add' => 'Regel hinzufügen',
        'edit' => 'Bearbeiten',
        'back' => 'Zurück zur Preisliste',
        'any_day' => 'Beliebig',

        'fields' => [
            'weekday' => 'Wochentag',
            'starts_at' => 'Beginn',
            'ends_at' => 'Ende',
            'slot_price' => 'Slot-Preis',
            'capacity' => 'Kapazität',
            'is_active' => 'Aktiv',
        ],

        'hints' => [
            'weekday' => 'Leer = Regel gilt für jeden Tag',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Overrides
    |--------------------------------------------------------------------------
    */
    'priceoverrides' => [
        'title' => 'Ausnahmen',
        'add' => 'Ausnahme hinzufügen',
        'edit' => 'Bearbeiten',
        'back' => 'Zurück zur Preisliste',
        'delete_confirm' => 'Löschen?',

        'fields' => [
            'date' => 'Datum',
            'starts_at' => 'Von',
            'ends_at' => 'Bis',
            'slot_price' => 'Preis',
            'capacity' => 'Kapazität',
            'is_active' => 'Aktiv',
        ],
    ],
];
