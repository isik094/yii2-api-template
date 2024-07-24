<?php

declare(strict_types=1);

use common\helpers\EnvHelper;

$db_host = EnvHelper::get(name: 'DB_HOST');
$db_name = EnvHelper::get(name: 'DB_NAME');

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => "mysql:host={$db_host};dbname={$db_name}",
            'username' => EnvHelper::get(name: 'DB_USER'),
            'password' => EnvHelper::get(name: 'DB_PASS'),
            'charset' => EnvHelper::get(name: 'DB_CHARSET'),
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
];
