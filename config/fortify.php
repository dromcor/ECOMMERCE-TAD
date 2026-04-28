<?php

return [
    'guard' => 'web',
    'home' => '/productos',
    'passwords' => 'users',
    'username' => 'email',
    'limiters' => [
        'login' => null,
        'two-factor' => null,
    ],
    'features' => [
        // Enable registration and reset password
        \Laravel\Fortify\Features::registration(),
        \Laravel\Fortify\Features::resetPasswords(),
    ],
];
