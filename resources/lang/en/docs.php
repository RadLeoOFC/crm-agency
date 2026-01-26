<?php

return [
    'title' => 'CRM User Guide',
    'subtitle' => 'What this CRM is for and how to use it — module by module.',
    'nav' => 'Navigate',
    'sections' => [
        'overview' => 'What is this CRM',
        'setup_flow' => 'Typical Setup Flow',
        'modules' => 'Modules Explained',
        'role_matrix' => 'Who Can Do What',
        'faq' => 'FAQ',
        'glossary' => 'Glossary',
        'support' => 'Support'
        ],
    'overview' => [
        'p1' => 'This CRM helps an ad agency plan, price, and schedule ads across multiple platforms (Telegram, YouTube, Facebook, websites).',
        'p2' => 'It organizes inventory into price lists and time slots, applies rules and overrides, tracks bookings, promo codes, clients, users, roles, and interface languages.'
        ],
    'setup' => [
        '1' => 'Create Platforms (e.g., a Telegram channel or a YouTube channel) with currency, timezone, and status.',
        '2' => 'Create a Pricelist for a platform: pick currency, validity window, timezone, default slot duration.',
        '3' => 'Add Price Rules (weekly time windows with base price and capacity).',
        '4' => 'Add Price Overrides for specific dates (holidays, events).',
        '5' => 'Generate Slots from the pricelist rules/overrides.',
        '6' => 'Create Bookings using a slot or a custom time window; apply promo codes if needed.',
        '7' => 'Manage Clients, Users, Roles, and Languages.'
        ],
    'who_uses' => 'Who uses it:',
    'related' => 'Related:',
    'modules' => [
        'platforms' => [
            'name' => 'Platforms',
            'desc' => 'A platform is an advertising channel (Telegram, YouTube, Facebook, website) with its own currency/timezone.',
            'points' => [
    'Holds basic info: name, type, description, currency, timezone, active flag.',
    'Owns its Pricelists and Slots; Bookings tie back to a platform.',
    'Currency/timezone affect pricing and date/time handling.',
    'Deactivate a platform to pause its sales.'
                ],
            'who' => 'Admin/Manager manage platforms; other roles read if allowed.',
            'related' => 'Pricelists, Slots, Bookings'
            ],
        'pricelists' => [
            'name' => 'Pricelists',
            'desc' => 'A pricelist defines pricing configuration for one platform across a time period.',
            'points' => [
    'Includes currency, validity dates, timezone, slot duration (e.g., 60 minutes).',
    'Uses Price Rules and Price Overrides to compute future Slots.',
    'Only one or a few active pricelists per platform are recommended.',
    'Slots can be (re)generated when rules/overrides change.'
                ],
            'who' => 'Admin/Manager create and edit.',
            'related' => 'Price Rules, Price Overrides, Slots'
            ],
        'pricerules' => [
            'name' => 'Price Rules',
            'desc' => 'Weekly patterns (e.g., Mo-Fr 09:00–18:00) with base slot price and capacity.',
            'points' => [
    'Weekday is optional: blank means \'applies to all days\'.',
    'Defines time window, slot price, and capacity per slot.',
    'Cheapest way to define recurring pricing at scale.'
                ],
            'who' => 'Admin/Manager create and edit.',
            'related' => 'Pricelists, Price Overrides, Slots'
            ],
        'priceoverrides' => [
            'name' => 'Price Overrides',
            'desc' => 'Date-specific overrides (e.g., 2026‑01‑01) for special pricing or capacity.',
            'points' => [
    'Set for a concrete date with start/end time, price, and capacity.',
    'Takes precedence over weekly Price Rules for that date/time.',
    'Ideal for holidays, campaigns, peak demand.'
                ],
            'who' => 'Admin/Manager create and edit.',
            'related' => 'Pricelists, Price Rules, Slots'
            ],
        'slots' => [
            'name' => 'Slots',
            'desc' => 'Concrete time windows available for booking, generated from rules/overrides.',
            'points' => [
    'Each slot has start/end time, price, status (available/reserved/booked/cancelled).',
    'Capacity and used_capacity allow multiple ads per slot if desired.',
    'Filters by platform/status help find inventory fast.'
                ],
            'who' => 'Admin/Manager manage, Clients/Partners may view in self-service scenarios.',
            'related' => 'Pricelists, Price Rules, Price Overrides, Bookings'
            ],
        'bookings' => [
            'name' => 'Bookings',
            'desc' => 'Confirmed or pending ad placements with final pricing and promo discounts.',
            'points' => [
    'Create from a generated slot or a custom time range tied to a platform/pricelist.',
    'PricingService calculates list price, discount, and final price; stores currency and promo.',
    'Statuses: pending, confirmed, cancelled, completed.',
    'Slot capacity increments on booking; freeing a slot decrements it.'
                ],
            'who' => 'Admin/Manager manage bookings; Client/Partner can request/create own if enabled.',
            'related' => 'Slots, Promo Codes, Clients'
            ],
        'clients' => [
            'name' => 'Clients',
            'desc' => 'Advertiser records with contacts, VAT, geography, and active flag.',
            'points' => [
    'One client can have many bookings and promo redemptions.',
    'Stores contact person, email, phone, company, VAT, address.',
    'Toggle active to restrict new orders.'
                ],
            'who' => 'Admin/Manager maintain; Accountant see finance-related fields.',
            'related' => 'Bookings, Promo Codes'
            ],
        'promocodes' => [
            'name' => 'Promo Codes',
            'desc' => 'Discount codes (percent or fixed amount) with scope, limits, and validity window.',
            'points' => [
    'Scope: global / platform / pricelist; can be active or paused.',
    'Limits: total uses and per-client; optional min order amount.',
    'Fixed discounts require currency; codes may be stackable.'
                ],
            'who' => 'Admin/Manager create and manage.',
            'related' => 'Bookings, Clients'
            ],
        'users' => [
            'name' => 'Users',
            'desc' => 'Accounts for CRM access. Each user can have one or multiple roles.',
            'points' => [
    'Fields: name, email, Telegram chat id, password.',
    'Assign roles to grant permissions across modules.',
    'Edit or remove users when staff changes.'
                ],
            'who' => 'Admin only.',
            'related' => 'Roles'
            ],
        'roles' => [
            'name' => 'Roles',
            'desc' => 'Permission sets powered by Spatie Permission (e.g., admin, manager, accountant, partner, client).',
            'points' => [
    'Attach permissions (e.g., modules.create, modules.view) to roles.',
    'Assign roles to users to control access.',
    'Customize to match your business workflow.'
                ],
            'who' => 'Admin only.',
            'related' => 'Users'
            ],
        'languages' => [
            'name' => 'Languages',
            'desc' => 'UI language management. Add or edit language code and display name.',
            'points' => [
    'Lets users switch the interface language.',
    'Keeps translations consistent across modules.',
    'Add only languages you support with translations.'
                ],
            'who' => 'Admin manage; all users can switch.',
            'related' => 'All modules'
            ]
        ],
    'role_matrix' => [
        'module' => 'Module',
        'admin' => 'Admin',
        'manager' => 'Manager',
        'accountant' => 'Accountant',
        'partner' => 'Partner',
        'client' => 'Client',
        'legend' => 'Legend: C=create, R=read, U=update, D=delete. \'CR (self)\' means a client can create/read own bookings.'
        ],
    'faq' => [
        'q1' => 'How do I change pricing for one special day?',
        'a1' => 'Create a Price Override for that date and time. It takes precedence over weekly Price Rules.',
        'q2' => 'Why can’t I book?',
        'a2' => 'Check if there are generated slots for the period, if the platform/pricelist is active, and if capacity is available.',
        'q3' => 'How do promo codes work?',
        'a3' => 'Enter a code during booking. The system validates scope, date window, limits, and applies the discount.'
        ],
    'glossary' => [
        'platform' => 'Platform',
        'pricelist' => 'Pricelist',
        'rule' => 'Price Rule',
        'override' => 'Price Override',
        'slot' => 'Slot',
        'booking' => 'Booking',
        'promocode' => 'Promo Code',
        'capacity' => 'Capacity',
        'stackable' => 'Stackable',
        'platform_def' => 'An advertising channel where ads are published (e.g., Telegram channel).',
        'pricelist_def' => 'A container for pricing configuration over a period for a platform.',
        'rule_def' => 'A recurring weekly time window with base price and capacity.',
        'override_def' => 'A date-specific rule that supersedes weekly rules for that date/time.',
        'slot_def' => 'A concrete bookable time window generated from rules/overrides.',
        'booking_def' => 'A reservation for a slot/time window with a computed price and status.',
        'promocode_def' => 'A discount code that can reduce the final price based on conditions.',
        'capacity_def' => 'Maximum ads allowed in a slot; used_capacity tracks how many are taken.',
        'stackable_def' => 'Whether a promo can be combined with other promos.'
        ],
    'support' => [
        'p1' => 'Need help? Contact your administrator or check the internal knowledge base.',
        'p2' => 'For bugs or feature requests, create a ticket with steps to reproduce.'
        ]
];
