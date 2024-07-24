<?php

declare(strict_types=1);

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('Уникальный идентификатор'),
            'username' => $this->string()->notNull()->unique()->comment('Никнейм'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ авторизации'),
            'password_hash' => $this->string()->notNull()->comment('Хэш пароля'),
            'password_reset_token' => $this->string()->unique()->comment('Токен сброса пароля'),
            'email' => $this->string()->notNull()->unique()->comment('Электронная почта'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('Статус'),
            'created_at' => $this->integer()->notNull()->comment('Время создания'),
            'updated_at' => $this->integer()->notNull()->comment('Время обновления'),
        ], $tableOptions);

        $this->addCommentOnTable('user', 'Пользователь');
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropTable('{{%user}}');
    }
}
