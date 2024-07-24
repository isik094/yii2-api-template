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
            // send all mails to a file by default.
            'useFileTransport' => true,
            // You have to set
            //
            // 'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
            //    'transport' => [
            //        'scheme' => 'smtps',
            //        'host' => '',
            //        'username' => '',
            //        'password' => '',
            //        'port' => 465,
            //        'dsn' => 'native://default',
            //    ],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://user:pass@smtp.example.com:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
