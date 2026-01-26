<?php

return [
    'title' => 'CRM-Benutzerhandbuch',
    'subtitle' => 'Wofür dieses CRM gedacht ist und wie man es nutzt — Modul für Modul.',
    'nav' => 'Navigation',
    'sections' => [
        'overview' => 'Was ist dieses CRM',
        'setup_flow' => 'Typischer Einrichtungsablauf',
        'modules' => 'Module erklärt',
        'role_matrix' => 'Wer darf was',
        'faq' => 'FAQ',
        'glossary' => 'Glossar',
        'support' => 'Support'
        ],
    'overview' => [
        'p1' => 'Das CRM hilft Agenturen, Anzeigen über mehrere Plattformen (Telegram, YouTube, Facebook, Websites) zu planen, zu bepreisen und zu terminieren.',
        'p2' => 'Es organisiert Preise in Preislisten und Zeitslots, wendet Regeln und Ausnahmen an, verfolgt Buchungen, Promo-Codes, Kunden, Benutzer, Rollen und Sprachen.'
        ],
    'setup' => [
        '1' => 'Plattformen anlegen (z. B. Telegram‑ oder YouTube‑Kanal) mit Währung, Zeitzone und Status.',
        '2' => 'Preisliste für die Plattform anlegen: Währung, Gültigkeit, Zeitzone, Slot‑Dauer.',
        '3' => 'Preisregeln hinzufügen (wöchentliche Zeitfenster mit Preis und Kapazität).',
        '4' => 'Preis‑Ausnahmen für bestimmte Daten hinzufügen (Feiertage, Events).',
        '5' => 'Slots aus Regeln/Ausnahmen generieren.',
        '6' => 'Buchungen per Slot oder freiem Zeitraum erstellen; ggf. Promo‑Codes anwenden.',
        '7' => 'Kunden, Benutzer, Rollen und Sprachen pflegen.'
        ],
    'who_uses' => 'Wer nutzt es:',
    'related' => 'Verwandte Module:',
    'modules' => [
        'platforms' => [
            'name' => 'Plattformen',
            'desc' => 'Eine Plattform ist ein Werbekanal (Telegram, YouTube, Facebook, Website) mit eigener Währung/Zeitzone.',
            'points' => [
    'Speichert Name, Typ, Beschreibung, Währung, Zeitzone, Aktiv-Status.',
    'Besitzt eigene Preislisten und Slots; Buchungen referenzieren die Plattform.',
    'Währung/Zeitzone beeinflussen Preisrechnung und Zeitangaben.',
    'Deaktivieren, um Verkäufe temporär zu pausieren.'
                ],
            'who' => 'Admin/Manager verwalten; andere lesen ggf.',
            'related' => 'Preislisten, Slots, Buchungen'
            ],
        'pricelists' => [
            'name' => 'Preislisten',
            'desc' => 'Definiert Preis-Konfiguration für eine Plattform über einen Zeitraum.',
            'points' => [
    'Enthält Währung, Gültigkeit, Zeitzone, Slot‑Dauer (z. B. 60 Min).',
    'Nutzt Preisregeln und Preis‑Ausnahmen zum Erzeugen künftiger Slots.',
    'Empfehlung: wenige aktive Preislisten pro Plattform.',
    'Slots können bei Regeländerung (neu)generiert werden.'
                ],
            'who' => 'Admin/Manager',
            'related' => 'Preisregeln, Preis‑Ausnahmen, Slots'
            ],
        'pricerules' => [
            'name' => 'Preisregeln',
            'desc' => 'Wöchentliche Muster (z. B. Mo‑Fr 09:00–18:00) mit Basispreis und Kapazität.',
            'points' => [
    'Wochentag optional: leer = „für alle Tage“.',
    'Definiert Zeitfenster, Slot‑Preis und Kapazität.',
    'Schnellste Art wiederkehrende Preise zu pflegen.'
                ],
            'who' => 'Admin/Manager',
            'related' => 'Preislisten, Preis‑Ausnahmen, Slots'
            ],
        'priceoverrides' => [
            'name' => 'Preis‑Ausnahmen',
            'desc' => 'Datumsbezogene Ausnahmen (z. B. 2026‑01‑01) für Spezialpreise/-kapazität.',
            'points' => [
    'Für ein konkretes Datum mit Start/Ende, Preis und Kapazität.',
    'Hat Vorrang vor wöchentlichen Preisregeln.',
    'Ideal für Feiertage, Kampagnen, Peaks.'
                ],
            'who' => 'Admin/Manager',
            'related' => 'Preislisten, Preisregeln, Slots'
            ],
        'slots' => [
            'name' => 'Slots',
            'desc' => 'Konkrete buchbare Zeitfenster, generiert aus Regeln/Ausnahmen.',
            'points' => [
    'Start/Ende, Preis, Status (available/reserved/booked/cancelled).',
    'Kapazität und used_capacity erlauben mehrere Anzeigen je Slot.',
    'Filter nach Plattform/Status erleichtern die Suche.'
                ],
            'who' => 'Admin/Manager verwalten; Kunden/Partner können ggf. einsehen.',
            'related' => 'Preislisten, Preisregeln, Preis‑Ausnahmen, Buchungen'
            ],
        'bookings' => [
            'name' => 'Buchungen',
            'desc' => 'Bestätigte oder ausstehende Schaltungen mit Endpreis und Rabatten.',
            'points' => [
    'Aus einem Slot oder freiem Zeitraum; an Plattform/Preisliste gebunden.',
    'PricingService berechnet Listenpreis, Rabatt und Endpreis; speichert Währung/Promo.',
    'Status: pending, confirmed, cancelled, completed.',
    'Slot‑Kapazität wird bei Buchung hoch-/bei Freigabe heruntergezählt.'
                ],
            'who' => 'Admin/Manager verwalten; Kunde/Partner ggf. eigene anlegen.',
            'related' => 'Slots, Promo‑Codes, Kunden'
            ],
        'clients' => [
            'name' => 'Kunden',
            'desc' => 'Werbetreibende mit Kontakten, USt‑Id, Standort und Aktivitätsstatus.',
            'points' => [
    'Ein Kunde hat viele Buchungen und Promo‑Einlösungen.',
    'Speichert Ansprechpartner, E‑Mail, Telefon, Firma, USt‑Id, Adresse.',
    'Aktivität ausschalten, um neue Aufträge zu sperren.'
                ],
            'who' => 'Admin/Manager pflegen; Buchhalter Sicht auf relevante Felder.',
            'related' => 'Buchungen, Promo‑Codes'
            ],
        'promocodes' => [
            'name' => 'Promo‑Codes',
            'desc' => 'Rabattcodes (Prozent oder Fixbetrag) mit Geltungsbereich, Limits und Zeitraum.',
            'points' => [
    'Scope: global / Plattform / Preisliste; aktivierbar/deaktivierbar.',
    'Limits: Gesamt und pro Kunde; optionaler Mindestbestellwert.',
    'Fixe Rabatte brauchen Währung; Codes ggf. kombinierbar.'
                ],
            'who' => 'Admin/Manager',
            'related' => 'Buchungen, Kunden'
            ],
        'users' => [
            'name' => 'Benutzer',
            'desc' => 'Zugänge zum CRM. Benutzer können mehrere Rollen haben.',
            'points' => [
    'Felder: Name, E‑Mail, Telegram‑Chat‑ID, Passwort.',
    'Rollen steuern Berechtigungen über Module hinweg.',
    'Bearbeiten oder löschen bei Teamänderungen.'
                ],
            'who' => 'Nur Admin.',
            'related' => 'Rollen'
            ],
        'roles' => [
            'name' => 'Rollen',
            'desc' => 'Berechtigungssätze via Spatie Permission (z. B. admin, manager, accountant, partner, client).',
            'points' => [
    'Rechte (z. B. modules.create, modules.view) Rollen zuordnen.',
    'Rollen an Benutzer vergeben für Zugriffskontrolle.',
    'An Prozesse Ihres Unternehmens anpassen.'
                ],
            'who' => 'Nur Admin.',
            'related' => 'Benutzer'
            ],
        'languages' => [
            'name' => 'Sprachen',
            'desc' => 'Verwaltung der UI‑Sprache. Sprachcode und -name pflegen.',
            'points' => [
    'Benutzer können die Sprache umschalten.',
    'Konsistente Übersetzungen über alle Module.',
    'Nur Sprachen hinzufügen, die Sie wirklich übersetzen.'
                ],
            'who' => 'Admin verwaltet; alle können umschalten.',
            'related' => 'Alle Module'
            ]
        ],
    'role_matrix' => [
        'module' => 'Modul',
        'admin' => 'Admin',
        'manager' => 'Manager',
        'accountant' => 'Buchhalter',
        'partner' => 'Partner',
        'client' => 'Kunde',
        'legend' => 'Legende: C=create, R=read, U=update, D=delete. „CR (self)“ = Kunde kann eigene Buchungen anlegen/sehen.'
        ],
    'faq' => [
        'q1' => 'Wie ändere ich den Preis für einen speziellen Tag?',
        'a1' => 'Erstellen Sie eine Preis‑Ausnahme für Datum/Zeit. Sie hat Vorrang vor wöchentlichen Regeln.',
        'q2' => 'Warum kann ich nicht buchen?',
        'a2' => 'Prüfen Sie, ob Slots generiert sind, Plattform/Preisliste aktiv ist und Kapazität vorhanden ist.',
        'q3' => 'Wie funktionieren Promo‑Codes?',
        'a3' => 'Geben Sie den Code bei der Buchung ein. Das System prüft Geltungsbereich, Zeitraum, Limits und wendet den Rabatt an.'
        ],
    'glossary' => [
        'platform' => 'Plattform',
        'pricelist' => 'Preisliste',
        'rule' => 'Preisregel',
        'override' => 'Preis‑Ausnahme',
        'slot' => 'Slot',
        'booking' => 'Buchung',
        'promocode' => 'Promo‑Code',
        'capacity' => 'Kapazität',
        'stackable' => 'Kombinierbar',
        'platform_def' => 'Werbekanal, in dem Anzeigen erscheinen (z. B. Telegram‑Kanal).',
        'pricelist_def' => 'Container für Preis‑Konfiguration einer Plattform über einen Zeitraum.',
        'rule_def' => 'Wiederkehrendes Wochenzeitfenster mit Basispreis und Kapazität.',
        'override_def' => 'Datumsbezogene Regel mit Vorrang vor wöchentlichen.',
        'slot_def' => 'Konkretes buchbares Zeitfenster aus Regeln/Ausnahmen.',
        'booking_def' => 'Reservierung mit berechnetem Endpreis und Status.',
        'promocode_def' => 'Rabattcode, der den Endpreis unter Bedingungen reduziert.',
        'capacity_def' => 'Max. Anzeigen pro Slot; used_capacity zeigt belegte.',
        'stackable_def' => 'Ob ein Code mit anderen kombinierbar ist.'
        ],
    'support' => [
        'p1' => 'Benötigen Sie Hilfe? Kontaktieren Sie Ihren Administrator oder die Wissensdatenbank.',
        'p2' => 'Für Bugs/Features erstellen Sie bitte ein Ticket mit Reproduktionsschritten.'
        ]
];
