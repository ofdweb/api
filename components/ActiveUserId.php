<?php
namespace app\components;
 
use Yii;
use yii\base\Object;
 
class ActiveUserId extends Object implements ActiveIdInterface
{
    public $default = '';
 
    public function get()
    {
        $user = Yii::$app->get('user', false);
        if ($user && !$user->getIsGuest()) {
            return $user->getId();
        }
        return $this->default;
    }
}