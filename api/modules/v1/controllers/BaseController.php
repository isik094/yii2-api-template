<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

use api\components\jwt\JwtHttpAuth;
use api\components\response\ApiResponse;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Общий контроллер по умолчанию для API v1
 */
abstract class BaseController extends Controller implements BaseControllerInterface
{
    /** @var string Модель для контроллера */
    protected string $modelClass;

    /**
     * Общее поведение контроллеров
     *
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'cors' => [
                'class' => Cors::class,
            ],
            'authenticator' => [
                'class' => JwtHttpAuth::class,
                'except' => $this->except(),
                'optional' => $this->optional(),
            ],
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => $this->accessRules(),
//            ],
        ];
    }

    /**
     * Тип - HTTP дефолтный без аутентификации
     *
     * @return array
     */
    public function except(): array
    {
        return ['options'];
    }

    /**
     * Массив методов с опциональной аутентификацией
     *
     * @return array
     */
    public function optional(): array
    {
        return [];
    }

//    /**
//     * Управление доступом
//     *
//     * @return array
//     */
//    protected function accessRules(): array
//    {
//        return [
//            [
//                'allow' => true,
//                'verbs' => ['options'],
//            ],
//        ];
//    }

    /**
     * Информация для запросов cors (временно, продумать)
     *
     * @return ApiResponse
     */
    public function actionOptions(): ApiResponse
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->getHeaders()->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->getHeaders()->set('Access-Control-Allow-Methods', '*');
        Yii::$app->response->getHeaders()->set('Access-Control-Allow-Headers', '*');
        Yii::$app->response->getHeaders()->set('Access-Control-Allow-Credentials');
        Yii::$app->response->getHeaders()->set('Access-Control-Max-Age', '86400');
        Yii::$app->response->getHeaders()->set('Access-Control-Expose-Headers', '');
        Yii::$app->getResponse()->setStatusCode(200);

        return new ApiResponse([
            'message' => 'OPTIONS request handled',
            'allowed_origin' => ['*'],
            'allowed_methods' => ['*'],
            'allowed_headers' => ['*'],
            'max_age' => 86400,
            'cache_control' => 'public, max-age=86400',
            'additional_info' => 'Some additional information about the API endpoint.',
        ]);
    }

    /**
     * Получить модель по id
     *
     * @param int $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): ActiveRecord
    {
        if (($model = $this->modelClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}