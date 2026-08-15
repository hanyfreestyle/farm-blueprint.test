<?php

return [
    'admin_panel_path' => env('ADMIN_PANEL_PATH', 'admin'),
    'locales' => [
        'admin_content' => ['ar', 'en'],
    ],
    'users' => [
        'author_profile_enabled' => env('CORE_USERS_AUTHOR_PROFILE_ENABLED', true),
    ],
];
