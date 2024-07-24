<?php

declare(strict_types=1);

namespace api\models;

use api\traits\TokenTrait;

/**
 * Модель невалдиных jwt (access_token) токенов
 */
class InvalidToken extends \common\models\InvalidToken
{
    use TokenTrait;

    /**
     * Получить невальдный токен
     *
     * @param string $token
     * @return static|null
     */
    public static function getInvalidToken(string $token): ?static
    {
        return static::findOne(['token' => $token]);
    }
}