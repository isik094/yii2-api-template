<?php

declare(strict_types=1);

namespace common\models;

use api\helpers\JwtHelper;
use common\enums\user\UserStatusEnum;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Token $token
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Очистить все токены пользователя при смене пароля
     *
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes): void
    {
        if (array_key_exists('password_hash', $changedAttributes)) {
            Token::deleteAll(['user_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => UserStatusEnum::inactive->value],
            ['status', 'in', 'range' => UserStatusEnum::getValues()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity(mixed $id): ?static
    {
        return static::findOne([
            'id' => $id,
            'status' => UserStatusEnum::active->value
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken(mixed $token, mixed $type = null): ?IdentityInterface
    {
        return static::findOne([
            'id' => JwtHelper::getUserId(token: $token),
            'status' => UserStatusEnum::active->value,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Gets query for [[Token]].
     *
     * @return ActiveQuery
     */
    public function getToken(): ActiveQuery
    {
        return $this->hasMany(Token::class, ['user_id' => 'id']);
    }
}
