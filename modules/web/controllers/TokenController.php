<?php

namespace app\modules\web\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Default controller for the `web` module
 */
class TokenController extends ActiveController
{
    public $modelClass = 'app\models\Account';
  
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }
}
