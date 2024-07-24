<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invalid_token}}`.
 */
class m240406_134452_create_invalid_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%invalid_token}}', [
            'id' => $this->primaryKey()->comment('Уникальный идентификатор'),
            'user_id' => $this->integer()->comment('Id пользователя'),
            'token' => $this->string(400)->notNull()->unique()->comment('Акесес jwt токен'),
            'ip' => $this->string(40)->comment('IP пользователя'),
            'user_agent' => $this->text()->comment('Пользовательский агент'),
            'created_at' => $this->integer()->comment('Время создания'),
        ]);

        $this->addCommentOnTable('invalid_token', 'Невалидный jwt аксес токен');

        $this->createIndex(
            '{{%idx-invalid_token-user_id}}',
            '{{%invalid_token}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-invalid_token-user_id}}',
            '{{%invalid_token}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey(
            '{{%fk-invalid_token-user_id}}',
            '{{%invalid_token}}'
        );

        $this->dropIndex(
            '{{%idx-invalid_token-user_id}}',
            '{{%invalid_token}}'
        );

        $this->dropTable('{{%invalid_token}}');
    }
}
