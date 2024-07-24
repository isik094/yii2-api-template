<?php

declare(strict_types=1);

namespace api\modules\v1\modules\auth\models\forms;

use api\helpers\JwtHelper;
use api\models\User;
use common\enums\user\UserStatusEnum;
use common\helpers\EnvHelper;
use yii\base\Model;

/**
 * Форма генерация access_token
 */
class RefreshForm extends Model
{
    public ?User $user = null;
    public ?string $refresh;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['refresh', 'required'],
            ['refresh', 'trim'],
            ['refresh', 'string'],
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

        $this->user = User::find()
            ->joinWith('token')
            ->where(['and',
                ['user.id' => $user_id],
                ['user.status' => UserStatusEnum::active->value],
                ['token.user_id' => $user_id],
                ['token.token' => $this->refresh],
                ['>', 'token.created_at', time() - EnvHelper::get(name: 'JWT_REFRESH_EXPIRE')]
            ])
            ->one();

        if ($this->user === null) {
            $this->addError(attribute: $attribute, error: 'Токен не валидный или истек срок действия.');
        }
    }

    /**
     * Сгенерировать access_token
     *
     * @return array|null
     */
    public function getAccessToken(): ?array
    {
        if (!$this->validate()) {
            return null;
        }

        return JwtHelper::generateTokens(user: $this->user, refresh: $this->refresh);
    }
}