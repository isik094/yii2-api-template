<?php

declare(strict_types=1);

namespace api\modules\v1\modules\auth\models\forms;

use api\helpers\JwtHelper;
use api\models\InvalidToken;
use api\models\Token;
use api\models\User;
use yii\base\Model;

/**
 * Форма выхода из системы
 */
class LogoutForm extends Model
{
    public User $user;
    public ?string $access;
    public ?string $refresh;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user', 'access', 'refresh'], 'required'],
            [['access', 'refresh'], 'trim'],
            [['access', 'refresh'], 'string'],
            ['refresh', 'refreshValidate'],
        ];
    }

    /**
     * @param string $attribute
     * @return void
     */
    public function refreshValidate(string $attribute): void
    {
        $user_id = $this->refresh
            ? JwtHelper::getUserId(token: $this->refresh)
            : null;

        if ($user_id !== $this->user->id) {
            $this->addError(attribute: $attribute, error: 'Токен не валидный или истек срок действия.');
        }
    }

    /**
     * Выход из системы и удаление токенов
     *
     * @return bool
     */
    public function logout(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if (
            (new InvalidToken)->add(user: $this->user, token: $this->access)
            && Token::deleteAll(['user_id' => $this->user->id, 'token' => $this->refresh])
        ) {
            return true;
        }

        return false;
    }
}