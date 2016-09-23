<?php

return [
    'lock_timeout'  => env('CAS_LOCK_TIMEOUT', 5000),
    'ticket_expire' => env('CAS_TICKET_EXPIRE', 300),
    'ticket_len'    => env('CAS_TICKET_LEN', 32),
    'user_table'    => [
        'id'    => 'id',
        'name'  => 'users',
        'model' => \App\User::class, //change to your user model class
    ],
    'router'        => [
        'prefix'      => 'cas',
        'name_prefix' => 'cas.',
    ],
    'middleware'    => [
        'common' => 'web',
        'auth'   => 'auth',
    ],
];
