<?php

declare(strict_types=1);

namespace api\modules\v1\modules\auth\controllers;

use api\components\response\ApiResponse;
use api\components\response\ApiResponseException;
use api\helpers\JwtHelper;
use api\models\User;
use api\modules\v1\controllers\BaseController;
use api\modules\v1\modules\auth\models\forms\LoginForm;
use api\modules\v1\modules\auth\models\forms\LogoutForm;
use api\modules\v1\modules\auth\models\forms\RefreshForm;
use api\modules\v1\modules\auth\models\forms\SignUpForm;

/**
 * Контроллер авторизации
 */
class AuthController extends BaseController
{
    /**
     * @return array
     */
    public function except(): array
    {
        return array_merge(parent::except(), [
            'sign-up',
            'login',
            'refresh',
        ]);
    }

    /**
     * Регистрация пользователя
     *
     * @return ApiResponse|ApiResponseException
     */
    public function actionSignUp(): ApiResponse|ApiResponseException
    {
        try {
            $model = new SignUpForm();
            $model->email = \Yii::$app->request->post(name: 'email');
            $model->password = \Yii::$app->request->post(name: 'password');

            if ($user = $model->signUp()) {
                return new ApiResponse(data: JwtHelper::generateTokens($user));
            }

            return new ApiResponse(data: $model->errors, error: true);
        } catch (\Exception $exception) {
            return new ApiResponseException(exception: $exception);
        }
    }

    /**
     * Авторизация пользователя
     *
     * @return ApiResponse|ApiResponseException
     */
    public function actionLogin(): ApiResponse|ApiResponseException
    {
        try {
            $model = new LoginForm();
            $model->email = \Yii::$app->request->post(name: 'email');
            $model->password = \Yii::$app->request->post(name: 'password');

            if ($user = $model->login()) {
                return new ApiResponse(data: JwtHelper::generateTokens($user));
            }

            return new ApiResponse(data: $model->errors, error: true);
        } catch (\Exception $exception) {
            return new ApiResponseException(exception: $exception);
        }
    }

    /**
     * Получение нового access_token
     *
     * @return ApiResponse|ApiResponseException
     */
    public function actionRefresh(): ApiResponse|ApiResponseException
    {
        try {
            $model = new RefreshForm();
            $model->refresh = \Yii::$app->request->post(name: 'refresh');

            if ($data = $model->getAccessToken()) {
                return new ApiResponse(data: $data);
            }
            return new ApiResponse(data: $model->errors, error: true);
        } catch (\Exception $exception) {
            return new ApiResponseException(exception: $exception);
        }
    }

    /**
     * Разлогинить пользователя
     *
     * @return ApiResponse|ApiResponseException
     */
    public function actionLogout(): ApiResponse|ApiResponseException
    {
        try {
            $model = new LogoutForm(['user' => User::getCurrent()]);
            $model->access = JwtHelper::getToken();
            $model->refresh = \Yii::$app->request->post('refresh');

            if ($model->logout()) {
                return new ApiResponse(data: ['success']);
            }

            return new ApiResponse(data: $model->errors, error: true);
        } catch (\Exception $exception) {
            return new ApiResponseException(exception: $exception);
        }
    }
}
