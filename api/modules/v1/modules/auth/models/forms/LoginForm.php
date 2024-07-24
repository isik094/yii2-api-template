<?php

declare(strict_types=1);

namespace api\modules\v1\modules\auth\models\forms;

use api\models\User;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    private ?User $user = null;
    public ?string $email;
    public ?string $password;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'trim'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return User|null whether the user is logged in successfully
     */
    public function login(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        return $this->user;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->user === null) {
            $this->user = User::findByEmail(email: $this->email);
        }

        return $this->user;
    }
}
