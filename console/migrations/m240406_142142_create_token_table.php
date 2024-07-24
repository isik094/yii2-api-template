<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m240406_142142_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey()->comment('Уникальный идентификатор'),
            'user_id' => $this->integer()->notNull()->comment('Id пользователя'),
            'token' => $this->string(400)->notNull()->unique()->comment('Рефреш токен jwt'),
            'ip' => $this->string(40)->comment('Ip пользователя'),
            'user_agent' => $this->text()->comment('Пользовательский агент'),
            'created_at' => $this->integer()->comment('Время создания'),
        ]);

        $this->addCommentOnTable('token', 'Рефреш токен jwt');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-token-user_id}}',
            '{{%token}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-token-user_id}}',
            '{{%token}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-token-user_id}}',
            '{{%token}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-token-user_id}}',
            '{{%token}}'
        );

        $this->dropTable('{{%token}}');
    }
}
