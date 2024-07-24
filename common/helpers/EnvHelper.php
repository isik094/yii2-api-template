<?php

declare(strict_types=1);

namespace common\helpers;

/**
 * Хелпер переменных окружения
 */
class EnvHelper
{
    /**
     * Получить переменную окружения из корневого файла .env
     * если она есть, иначе значение по умолчанию.
     *
     * Не используем getenv из-за того, что эти функции не являются потокобезопасными
     *
     * @param string $name
     * @param mixed|null $default
     * @return string|null
     */
    public static function get(string $name, string $default = null): ?string
    {
        return $_ENV[$name] ?? $default;
    }
}