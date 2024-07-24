<?php

declare(strict_types=1);

namespace api\helpers;

use api\models\Token;
use api\models\User;
use common\helpers\EnvHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use Yii;

class JwtHelper
{
    /**
     * Генерация парных токенов
     *
     * @param User $user
     * @param string|null $refresh
     * @return array
     */
    public static function generateTokens(User $user, ?string $refresh = null): array
    {
        return [
            'access_token' => JwtHelper::generateToken(user: $user),
            'refresh_token' => $refresh ?? JwtHelper::generateToken(user: $user, refreshToken: true),
        ];
    }

    /**
     * Генерация jwt токена
     *
     * @param User $user
     * @param bool $refreshToken
     * @return string
     */
    public static function generateToken(User $user, bool $refreshToken = false): string
    {
        $currentTime = time();
        $expire = $refreshToken === false
            ? EnvHelper::get(name: 'JWT_ACCESS_EXPIRE')
            : EnvHelper::get(name: 'JWT_REFRESH_EXPIRE');

        $token = JWT::encode([
            'issuer' => EnvHelper::get(name: 'JWT_ISSUER'),
            'audience' => EnvHelper::get(name: 'JWT_AUDIENCE'),
            'issued_at' => $currentTime,
            'expire' => $currentTime + $expire,
            'user_id' => $user->id,
        ], EnvHelper::get(name: 'JWT_KEY'), EnvHelper::get(name: 'JWT_ALGORITHM'));

        if ($refreshToken === true) {
            (new Token())->add(user: $user, token: $token);
        }

        return $token;
    }

    /**
     * Проверка на валидность токена
     *
     * @param string $token
     * @return stdClass|null
     */
    public static function validateToken(string $token): ?stdClass
    {
        try {
            return JWT::decode($token, new Key(EnvHelper::get(name: 'JWT_KEY'), EnvHelper::get(name: 'JWT_ALGORITHM')));
        } catch (\Exception $e) {
            Yii::error("JwtHelper::validateToken: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Получить id пользователя из jwt токена
     *
     * @param string $token
     * @return int|null
     */
    public static function getUserId(string $token): ?int
    {
        return self::validateToken(token: $token)?->user_id;
    }

    /**
     * Получить токен jwt из заголовка аутентификации
     *
     * @return string|null
     */
    public static function getToken(): ?string
    {
        $accessToken = Yii::$app->request->getHeaders()->get('Authorization');

        if ($accessToken === null) {
            return null;
        }

        return trim(str_replace('Bearer ', '', $accessToken));
    }
}