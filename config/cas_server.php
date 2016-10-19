<?php

return [
    'allow_reset_pwd'   => env('CAS_SERVER_ALLOW_RESET_PWD', true),
    'allow_register'    => env('CAS_SERVER_ALLOW_REGISTER', true),
    'disable_pwd_login' => env('CAS_SERVER_DISABLE_PASSWORD_LOGIN', false),
];
