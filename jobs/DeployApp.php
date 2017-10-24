<?php

namespace app\jobs;

use Yii;

/**
 * Class DeployApp.
 */
class DeployApp extends \yii\base\Object implements \yii\queue\Job
{ 
    public $account_id;

    public $token;
  
    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $model = new \app\models\Account([
            'account_id' => $this->account_id
        ]);
      
        if ($model->signup()) {
            $model->encryptToken();
          
            if (!$model->hasErrors()) {
                $this->token = $model->token;
            } else {
                Yii::error($model->getErrors(), \yii\queue\Queue::class);
            }
        } else {
            Yii::error($model->getErrors(), \yii\queue\Queue::class);
        }
      
        Yii::$app->queueWeb->push(new SignupAccount([
            'account_id' => $this->account_id,
            'token' => $this->token
        ]));

        var_dump('Signup token: ' . $this->token);
    }
}