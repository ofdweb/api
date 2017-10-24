<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tokens`.
 */
class m171013_085325_create_tokens_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tokens', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tokens');
    }
}
