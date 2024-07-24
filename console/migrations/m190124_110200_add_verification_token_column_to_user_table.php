<?php

declare(strict_types=1);

use \yii\db\Migration;

class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->addColumn(
            '{{%user}}',
            'verification_token',
            $this->string()->defaultValue(null)->comment('Токен верифиакции')
        );
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropColumn('{{%user}}', 'verification_token');
    }
}
