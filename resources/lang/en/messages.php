<?php

return [
    'welcome_user_title' => 'Welcome to the advertising space management system!',
    'welcome_user_description' => 'Submit advertising placement requests and we will place your ads on our platforms.',
    'go_to_dashboard' => 'Go to dashboard',
    'register' => 'Register',
    'login' => 'Login',
    'code' => 'Code',
    'name' => 'Name',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'languages' => 'Languages',
    'add_language' => 'Add language',
    'language_added' => 'Language successfully added',
    'actions' => 'Actions',
    'back' => 'Back',
    'cancel' => 'Cancel',
    'save' => 'Save',
    'create' => 'Create',

    /*
    |--------------------------------------------------------------------------
    | Platforms
    |--------------------------------------------------------------------------
    */
    'platforms' => [
        'title' => 'Platforms',
        'add' => 'Add platform',
        'edit' => 'Edit platform',
        'create' => 'Create platform',
        'update' => 'Update platform',
        'back' => 'Back to list',

        'fields' => [
            'name' => 'Platform name',
            'type' => 'Platform type',
            'description' => 'Description',
            'currency' => 'Currency',
            'timezone' => 'Timezone',
            'status' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],

        'actions' => [
            'actions' => 'Actions',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'confirm_delete' => 'Are you sure you want to delete this platform?',
        ],

        'types' => [
            'telegram' => 'Telegram',
            'youtube' => 'YouTube',
            'facebook' => 'Facebook',
            'website' => 'Website',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Lists
    |--------------------------------------------------------------------------
    */
    'pricelists' => [
        'title' => 'Price Lists',
        'title_show' => 'Price List',
        'information' => 'Information',
        'all' => 'All Price Lists',
        'title_edit' => 'Edit Price List',
        'title_create' => 'Add New Pricelist',
        'update_button' => 'Update Pricelist',
        'create_button' => 'Create Pricelist',
        'view' => 'View',

        'fields' => [
            'platform' => 'Platform',
            'name' => 'Name',
            'currency' => 'Currency',
            'period' => 'Period',
            'timezone' => 'Timezone',
            'active' => 'Active',
            'slot_duration' => 'Slot duration (min)',
            'valid_from' => 'Valid from',
            'valid_to' => 'Valid to',
        ],

        'links' => [
            'rules' => 'Rules',
            'overrides' => 'Overrides',
        ],

        'confirm_delete' => 'Delete pricelist?',
        'generate_slots' => 'Generate slots',
    ],

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    */
    'clients' => [
        'title' => 'Clients',
        'add' => 'Add client',
        'title_edit' => 'Edit Client',
        'title_create' => 'Add New Client',
        'update_button' => 'Update client',
        'create_button' => 'Create Client',

        'fields' => [
            'name' => 'Name',
            'contact_person' => 'Contact person',
            'email' => 'Email',
            'phone' => 'Phone',
            'company' => 'Company',
            'vat' => 'VAT number',
            'country' => 'Country',
            'city' => 'City',
            'address' => 'Address',
            'active' => 'Active',
        ],

        'confirm_delete' => 'Are you sure you want to delete this client?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Promocodes
    |--------------------------------------------------------------------------
    */
    'promocodes' => [
        'title' => 'Promo codes',
        'title_edit' => 'Edit Promocode',
        'title_create' => 'Create Promocode',
        'update_button' => 'Update Promocode',
        'create_button' => 'Create Promocode',

        'fields' => [
            'code' => 'Code',
            'type' => 'Type',
            'value' => 'Value',
            'window' => 'Window',
            'applies_to' => 'Applies to',
            'active' => 'Active',

            'discount_type' => 'Discount type',
            'discount_value' => 'Discount value',
            'currency' => 'Currency',
            'starts_at' => 'Starts at',
            'ends_at' => 'Ends at',
            'min_order' => 'Minimum order',
            'limit_per_client' => 'Limit per client',
            'limit_total' => 'Total usage limit',
            'stackable' => 'Stackable',
        ],

        'types' => [
            'percent' => 'Percent',
            'fixed' => 'Fixed amount',
        ],

        'applies' => [
            'global' => 'Global',
            'platform' => 'Platform',
            'price_list' => 'Price list',
        ],

        'confirm_delete' => 'Delete promocode?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Slots
    |--------------------------------------------------------------------------
    */
    'slots' => [
        'title' => 'Slots',
        'edit_title' => 'Edit slot #:id',
        'confirm_delete' => 'Delete slot?',

        'fields' => [
            'weekday' => 'Weekday',
            'start' => 'Start',
            'end' => 'End',
            'price' => 'Slot price',
            'capacity' => 'Capacity',
            'used_capacity' => 'Used',
            'status' => 'Status',
            'platform' => 'Platform',
            'price_list' => 'Price list',
            'capacity_used' => 'Cap./Used',
            'empty' => '—',
        ],

        'filters' => [
            'all_platforms' => 'All platforms',
            'any_status' => 'Any status',
        ],

        'hints' => [
            'price_list_optional' => 'Price list (optional)',
            'not_specified' => '— not specified —',
            'used_le_capacity' => '≤ Capacity',
        ],

        'statuses' => [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'booked' => 'Booked',
            'cancelled' => 'Cancelled',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */
    'bookings' => [
        'title' => 'Bookings',
        'title_show' => 'Booking',
        'title_edit' => 'Edit Booking',
        'title_create' => 'Add New Booking',
        'add' => 'New booking',
        'edit' => 'Edit',
        'view' => 'View',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'actions' => 'Actions',
        'update_button' => 'Update Booking',
        'create_button' => 'Create Booking',
        'no_items' => 'No bookings found',
        'delete_confirm' => 'Delete booking?',
        'back' => 'Back to list',

        'fields' => [
            'platform' => 'Platform',
            'client' => 'Client',
            'platform_select' => 'Select a platform',
            'client_select' => 'Select a client',
            'period' => 'Period',
            'price' => 'Price',
            'status' => 'Status',
            'starts_at' => 'Start date',
            'ends_at' => 'End date',
            'promo_code' => 'Promo code',
            'notes' => 'Notes',
        ],

        'statuses' => [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    'users' => [
        'title' => 'Users',
        'title_edit' => 'Edit user',
        'update_button' => 'Update',
        'add' => 'New user',
        'edit' => 'Edit',
        'delete_confirm' => 'Are you sure you want to delete this user?',

        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
            'telegram_chat_id' => 'Telegram Chat ID',
            'password' => 'Password',
            'roles' => 'Roles',
        ],

        'password_hint' => 'Leave empty if you do not want to change it',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'title' => 'Roles',
        'list' => 'Role list',
        'add' => 'Add role',
        'edit' => 'Edit role',
        'create' => 'Create role',
        'delete' => 'Delete role',
        'update' => 'Update',
        'back' => 'Back',
        'delete_confirm' => 'Are you sure you want to delete this role?',
        'title_create' => 'Add New Role',
        'title_edit' => 'Edit role',
        'under_title_create' => 'Create New Role',
        'update_button' => 'Update',

        'fields' => [
            'name' => 'Role name',
            'permissions' => 'Permissions',
            'actions' => 'Actions',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Rules
    |--------------------------------------------------------------------------
    */
    'pricerules' => [
        'title' => 'Price Rules',
        'title_edit' => 'Edit Price Rule',
        'title_create' => 'Create Price Rule',
        'add' => 'Add rule',
        'edit' => 'Edit',
        'back' => 'Back to price list',
        'any_day' => 'Any',
        'confirm_delete' => 'Delete pricerule?',

        'fields' => [
            'weekday' => 'Weekday',
            'starts_at' => 'Starts at',
            'ends_at' => 'Ends at',
            'slot_price' => 'Slot price',
            'capacity' => 'Capacity',
            'is_active' => 'Active',
        ],

        'hints' => [
            'weekday' => 'Empty = rule applies to any day',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Overrides
    |--------------------------------------------------------------------------
    */
    'priceoverrides' => [
        'title' => 'Price Overrides',
        'title_edit' => 'Edit Price override',
        'title_create' => 'Create Price override',
        'update_button' => 'Update Price override',
        'add' => 'Add override',
        'edit' => 'Edit',
        'back' => 'Back to price list',
        'delete_confirm' => 'Delete?',

        'fields' => [
            'date' => 'Date',
            'starts_at' => 'From',
            'ends_at' => 'To',
            'slot_price' => 'Price',
            'capacity' => 'Capacity',
            'is_active' => 'Active',
        ],
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'welcome' => 'Welcome to the advertising platform',
        'go_home' => 'Go to homepage',
    ],


];
