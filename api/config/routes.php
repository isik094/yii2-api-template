<?php

return [
//    'v1/base/options' => 'v1/base/options',
//
//    // v1/auth
//    'v1/auth/sign-up' => 'v1/auth/auth/sign-up',
//    'v1/auth/login' => 'v1/auth/auth/login',
//    'v1/auth/refresh' => 'v1/auth/auth/refresh',
//    'v1/auth/logout' => 'v1/auth/auth/logout',
    'v1/<controller:[\w\d-]+>/<action:[\w\d-]+>' => 'v1/<controller>/<action>',
    'v1/<module:[\w\d-]+>/<controller:[\w\d-]+>/<action:[\w\d-]+>' => 'v1/<module>/<controller>/<action>',
];
