<?php

declare(strict_types = 1);

namespace common\models;

/**
 * This is the model class for table "file".
 *
 * @property int $id Уникальный идентификатор
 * @property int|null $user_id ID пользователя
 * @property int $entity Тип сущности
 * @property int $entity_id ID сущности
 * @property string $path Путь
 * @property int|null $created_at Время создания
 *
 * @property User $user
 */
class File extends \common\base\ActiveRecord
{
    /** @var int Файлы пользователя */
    public const USER_FILE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'entity', 'entity_id', 'created_at'], 'integer'],
            [['entity', 'entity_id', 'path'], 'required'],
            [['path'], 'string', 'max' => 400],
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
            'entity' => 'Entity',
            'entity_id' => 'Entity ID',
            'path' => 'Path',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
