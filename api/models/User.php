<?php

declare(strict_types=1);

namespace api\models;

use common\enums\user\UserStatusEnum;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * Класс пользователя
 */
class User extends \common\models\User
{
    /**
     * Получить текущего пользователя
     *
     * @return IdentityInterface|null
     */
    public static function getCurrent(): ?IdentityInterface
    {
        return \Yii::$app->user->identity;
    }

    /**
     * Установить статус пользователя
     *
     * @param UserStatusEnum $statusEnum
     * @return void
     */
    public function setStatus(UserStatusEnum $statusEnum): void
    {
        $this->status = $statusEnum->value;
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?static
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => UserStatusEnum::inactive->value,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?static
    {
        if (!static::isPasswordResetTokenValid(token: $token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => UserStatusEnum::active->value,
        ]);
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?static
    {
        return static::findOne(['email' => $email, 'status' => UserStatusEnum::active->value]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword(password: $password, hash: $this->password_hash);
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash(password: $password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}