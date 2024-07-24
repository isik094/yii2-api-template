<?php

declare(strict_types=1);

namespace api\traits;

use api\models\User;

/**
 * Функции для токенов
 */
trait TokenTrait
{
    /**
     * Добавить токен
     *
     * @param User $user
     * @param string $token
     * @return bool
     */
    public function add(User $user, string $token): bool
    {
        try {
            $model = new static();
            $model->user_id = $user->id;
            $model->token = $token;
            $model->ip = \Yii::$app->request->userIP;
            $model->user_agent = \Yii::$app->request->userAgent;
            $model->created_at = time();

            return $model->save();
        } catch (\Exception $exception) {
            \Yii::error(message: get_class($this) . "::add: {$exception->getMessage()}");

            return false;
        }
    }
}