<?php

declare(strict_types=1);

namespace api\modules\v1\modules\auth\models\forms;

use api\modules\v1\modules\user\models\data\User;
use common\enums\user\UserStatusEnum;
use yii\base\Exception;
use yii\base\Model;

/**
 * Регистрация пользователя
 */
class SignUpForm extends Model
{
    public ?string $email;
    public ?string $password;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'trim'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['email', 'uniqueValidate'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * @param string $attribute
     * @return void
     */
    public function uniqueValidate(string $attribute): void
    {
        if (User::find()->where(['email' => $this->email])->exists()) {
            $this->addError(attribute: $attribute, error: 'Такой пользователь уже существует');
        }
    }

    /**
     * Зарегистрировать пользователя
     *
     * @return User|null
     * @throws Exception
     */
    public function signUp(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->setStatus(statusEnum: UserStatusEnum::active);
        $user->setPassword(password: $this->password);
        $user->generateAuthKey();
        $user->save();

        return $user;
    }
}