<?php

return [

    'preloader' => [
        'enabled' => false,
    ],
    'logo' => '<h3>4M</h3>',
    'logo_img' => 'img/logo4M.jpg',
    'logo_img_class' => 'brand-image',
    'logo_img_alt' => 'Logo',

    'menu' => [
        [
            'text' => 'Language',
            'topnav_right' => true,
            'icon' => 'fas fa-fw fa-flag-usa',
            'submenu' => [
                [
                    'text'=>'English',
                    'icon' => 'fas fa-fw fa-flag-usa',
                    'url'=> 'switch-language/en'
                ],
                [
                    'text'=>'Russian',
                    'icon' => 'fas fa-fw fa-flag-ru',
                    'url'=> 'switch-language/ru'
                ],
                [
                    'text'=>'Bulgarian',
                    'icon' => 'fas fa-fw fa-flag-bg',
                    'url'=> 'switch-language/bg'
                ],
                [
                    'text'=>'German',
                    'icon' => 'fas fa-fw fa-flag-de',
                    'url'=> 'switch-language/de'
                ],
            ]
        ],

        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url' => 'admin/blog',
            'can' => 'manage-blog',
        ],
        [
            'text' => 'Dashboard',
            'url'  => 'dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'text' => 'Clients',
            'url'  => 'clients',
            'icon' => 'fas fa-fw fa-users',
        ],
        [
            'text' => 'User Management',
            'icon' => 'fas fa-fw fa-user-cog',
            'can'  => 'users.view',
            'submenu' => [
                [
                    'text' => 'All Users',
                    'url'  => 'users',
                    'icon' => 'fas fa-fw fa-users',
                ],
                [
                    'text' => 'Roles',
                    'url'  => 'roles',
                    'icon' => 'fas fa-fw fa-user-tag',
                ],
            ],
        ],
        [
            'text' => 'Platforms',
            'url'  => 'platforms',
            'icon' => 'fas fa-fw fa-bullhorn',
        ],
        [
            'text' => 'Bookings',
            'url'  => 'bookings',
            'icon' => 'fas fa-fw fa-calendar-check'
        ],
        [
            'text' => 'Price lists',
            'url'  => 'pricelists',
            'icon' => 'fas fa-fw fa-tags'
        ],
        [
            'text' => 'Promocodes',
            'url'  => 'promocodes',
            'icon' => 'fas fa-fw fa-ticket-alt'
        ],
        [
            'text' => 'Slots',
            'url'  => 'slots',
            'icon' => 'fas fa-fw fa-clock'
        ],
        [
            'text' => 'Languages',
            'url'  => 'languages',
            'icon' => 'fas fa-regular fa-font'
        ],
        [
            'text' => 'Help',
            'icon' => 'fas fa-life-ring',
            'route' => 'help.user',
        ],
        [
            'text' => 'Profile',
            'url'  => 'profile',
            'icon' => 'fas fa-fw fa-user',
        ],
    ],
];
