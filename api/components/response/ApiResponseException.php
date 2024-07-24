<?php

declare(strict_types=1);

namespace api\components\response;

use Exception;
use Yii;

/**
 * Класс для формирования ответа ошибки API
 */
class ApiResponseException
{
    /** @var string Сообщение ошибки */
    public string $message;

    /**
     * Формирование ответа API с ошибкой
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        Yii::error($exception, 'api');

        if (property_exists($exception, 'statusCode')) {
            Yii::$app->response->statusCode = $exception->statusCode;
        } else {
            Yii::$app->response->statusCode = 500;
        }

        $this->message = $exception->getMessage();
    }
}