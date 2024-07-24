<?php

declare(strict_types=1);

namespace common\enums;

interface EnumInterface
{
    /**
     * Получить название на русском enum
     *
     * @param int|string $value
     * @return string
     */
    public static function getName(int|string $value): string;

    /**
     * Получить словарь
     *
     * Получить название и значение enum для select, где ключ - name enum, значение value enum
     * @return array
     */
    public static function getDictionary(): array;

    /**
     * Получить массив значений enum
     *
     * @return array
     */
    public static function getValues(): array;
}
