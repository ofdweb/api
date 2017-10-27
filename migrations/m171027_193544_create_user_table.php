<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171027_193544_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->comment('Username'),
            'password' => $this->string()->comment('Password'),
            'authKey' => $this->string()->comment('Auth key'),
            'accessToken' => $this->string()->comment('Access token'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
