<?php

declare(strict_types=1);

namespace api\components\response;

/**
 * Класс для формирование ответа API
 */
class ApiResponse
{
    /** @var array Ответ данных */
    public array $data;

    /**
     * Формирование единого ответа API
     *
     * @param array $data
     * @param bool $error
     */
    public function __construct(array $data, bool $error = false)
    {
        $this->data = $data;

        if ($error === true) {
            \Yii::$app->response->statusCode = 422;
        }
    }
}