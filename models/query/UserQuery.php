<?php

namespace app\models\query;

/**
 * Class UserQuery
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * Access token condition
     * @param $token
     * @return $this
     */
    public function accessTokenIs($token)
    {
        return $this->andWhere(['accessToken' => $token]);
    }

    /**
     * Username condition
     * @param $username
     * @return $this
     */
    public function usernameIs($username)
    {
        return $this->andWhere(['username' => $username]);
    }
}
