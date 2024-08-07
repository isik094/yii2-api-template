<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(__DIR__, 2) . '/frontend');
Yii::setAlias('@backend', dirname(__DIR__, 2) . '/backend');
Yii::setAlias('@console', dirname(__DIR__, 2) . '/console');
Yii::setAlias('@api', dirname(__DIR__, 2) . '/api');
Yii::setAlias('@uploads', dirname(__DIR__, 2) . '/api/uploads');

// подключения переменной окружения использование через $_ENV или $_SERVER
Dotenv\Dotenv::createImmutable(__DIR__ . '/../..')->load();