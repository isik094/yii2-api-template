<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

/**
 * Интерфейс дефолтного контроллера для API v1
 */
interface BaseControllerInterface
{
    /**
     * Массив методов не трубется аутентификация
     *
     * @return array
     */
    public function except(): array;

    /**
     * Массиф методов с опциональной аутентификацией
     *
     * @return array
     */
    public function optional(): array;
}