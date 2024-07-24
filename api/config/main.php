<?php

declare(strict_types=1);

use api\modules\v1\modules\user\Module as V1UserModule;
use api\modules\v1\modules\auth\Module as V1AuthModule;
use api\modules\v1\Module;
use api\components\middleware\TokenMiddleware;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'tokenMiddleware'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => Module::class,
            'modules' => [
                'user' => ['class' => V1UserModule::class],
                'auth' => ['class' => V1AuthModule::class],
            ]
        ],
    ],
    'components' => [
        'tokenMiddleware' => [
            'class' => TokenMiddleware::class,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => require(Yii::getAlias('@api/config/routes.php')),
        ],
    ],
    'params' => $params,
];
