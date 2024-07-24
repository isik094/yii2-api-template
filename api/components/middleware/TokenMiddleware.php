<?php

declare(strict_types=1);

namespace api\components\middleware;

use api\components\response\ApiResponseException;
use api\helpers\JwtHelper;
use api\models\InvalidToken;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\ExitException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Проверка на валидность токена перед каждым запросом с токеном
 */
class TokenMiddleware extends Component implements BootstrapInterface
{
    /**
     * Проверка на валидность токена
     *
     * @param $app
     * @return void
     */
    public function bootstrap($app): void
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
            if ($accessToken = JwtHelper::getToken()) {
                try {
                    if (JwtHelper::validateToken($accessToken) === null) {
                        throw new BadRequestHttpException('Invalid JWT token');
                    }

                    if (InvalidToken::getInvalidToken($accessToken)) {
                        throw new UnauthorizedHttpException('Unauthorized');
                    }
                } catch (\Exception $exception) {
                    $this->errorResponse($exception);
                }
            }
        });
    }

    /**
     * Вернуть ошибку в формате json
     *
     * @param \Exception $exception
     * @return void
     * @throws ExitException
     */
    private function errorResponse(\Exception $exception): void
    {
        \Yii::$app->response->data = new ApiResponseException($exception);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->send();
        \Yii::$app->end();
    }
}
