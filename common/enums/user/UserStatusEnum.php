<?php

declare(strict_types=1);

namespace common\enums\user;

use common\enums\EnumInterface;
use common\enums\EnumTrait;
use InvalidArgumentException;

/**
 * Пример для enum проекта, методы реализованы для валидации в рамках проекта Yii2 (возможно и для других фреймворков),
 * словаря(select), и вывода названия значения enum
 *
 * Enum для статуса пользователя
 */
enum UserStatusEnum: int implements EnumInterface
{
    use EnumTrait;

    case active = 10;
    case inactive = 9;
    case deleted = 0;

    /**
     * @return array
     */
    public static function getDictionary(): array
    {
        return [
            self::active->value => 'Активный',
            self::inactive->value => 'Не активный',
            self::deleted->value => 'Удален',
        ];
    }

    /**
     * @param int|string $value
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getName(int|string $value): string
    {
        return self::getDictionary()[$value] ?? throw new InvalidArgumentException('UserStatusEnum: задано неизвестное значение');
    }
}
