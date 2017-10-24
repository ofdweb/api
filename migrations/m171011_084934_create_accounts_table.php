<?php

use yii\db\Migration;

/**
 * Handles the creation of table `accounts`.
 */
class m171011_084934_create_accounts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('accounts', [
            'account_id' => $this->string(32)->notNull()->append('PRIMARY KEY'),
            'db_name' => $this->string(32),
            'db_username' => $this->string(32),
            'db_password' => $this->string(32),
            'is_deleted' => $this->smallInteger(1)->defaultValue('0'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('accounts');
    }
}
