<?php

declare(strict_types=1);

namespace common\models;

use common\base\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "invalid_token".
 *
 * @property int $id Уникальный идентификатор
 * @property int|null $user_id Id пользователя
 * @property string $token Акесес jwt токен
 * @property string|null $ip IP пользователя
 * @property string|null $user_agent Пользовательский агент
 * @property int|null $created_at Время создания
 *
 * @property User $user
 */
class InvalidToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'invalid_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'token'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['user_agent'], 'string'],
            [['token'], 'string', 'max' => 400],
            [['ip'], 'string', 'max' => 40],
            [['token'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
