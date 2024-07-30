<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m240730_200834_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey()->comment('Уникальный идентификатор'),
            'user_id' => $this->integer()->comment('ID пользователя'),
            'entity' => $this->integer()->notNull()->comment('Тип сущности'),
            'entity_id' => $this->integer()->notNull()->comment('ID сущности'),
            'path' => $this->string(400)->notNull()->comment('Путь'),
            'created_at' => $this->integer()->comment('Время создания'),
        ]);
        $this->addCommentOnTable('{{%file}}','Файлы');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-file-user_id}}',
            '{{%file}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-file-user_id}}',
            '{{%file}}',
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
            '{{%fk-file-user_id}}',
            '{{%file}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-file-user_id}}',
            '{{%file}}'
        );

        $this->dropTable('{{%file}}');
    }
}
