<?php

declare(strict_types=1);

namespace common\enums;

trait EnumTrait
{
    /**
     * Получить массив значений enum (для валидации)
     *
     * @return array
     */
    public static function getValues(): array
    {
        return array_column(static::cases(), 'value');
    }
}