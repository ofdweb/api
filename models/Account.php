<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounts".
 *
 * @property string $account_id
 * @property string $db_name
 * @property string $db_username
 * @property string $db_password
 * @property integer $is_deleted
 */
class Account extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    private $_host_list = [
      '127.0.0.12'
    ];
  
    public $host;
    public $token;
  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['is_deleted'], 'integer'],
            [['account_id', 'db_name', 'db_username', 'account_id'], 'string', 'max' => 32],
            [['account_id'], 'unique'],
            [['host'], 'safe'],
            ['token', 'validateToken', 'skipOnEmpty' => true, 'skipOnError' => false],
            [['token'], 'string', 'max' => 128],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateToken($attribute, $params)
    {
        if (!Yii::$app->encrypter->decrypt($this->$attribute)) {
            $this->addError($attribute, 'Token not by decrypted');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'db_name' => 'Db Name',
            'db_username' => 'Db Username',
            'db_password' => 'Db Password',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'encryption' => [
                'class' => '\nickcv\encrypter\behaviors\EncryptionBehavior',
                'attributes' => ['db_name', 'db_username', 'db_password'],
            ],
        ];
    }
  
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['account_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = static::decryptTokenStatic($token);
        $model = $token ? static::findOne(['account_id' => $token->a]) : false;
      
        if ($model) {
          $model->host = $token->h;
        }

        return $model;
    }
  
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->account_id;
    }
  
    /**
     * @inheritdoc
     */
    public function getAuthKey() {}
  
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {}
  
    private function selectHost()
    {
        $host = current($this->_host_list);
        $this->setAttribute('host', $host);
    }

    /**
     *
     */
    private function generateDbConnect()
    {
        $security = Yii::$app->getSecurity();
      
        $this->setAttributes([
            'db_name' => $security->generateRandomString(8),
            'db_username' => $security->generateRandomString(8),
            'db_password' => $security->generateRandomString(8)
        ]);
    }

    /**
     * @param $token
     * @return false|mixed
     */
    public function decryptTokenStatic($token)
    {
        $token = Yii::$app->encrypter->decrypt($token);
        return $token ? json_decode($token) : false;
    }

    /**
     *
     */
    public function encryptToken()
    {
        $token = json_encode([
          's' => Yii::$app->security->generateRandomString(6), 
          'h' => $this->host, 
          'a' => $this->account_id
        ], JSON_FORCE_OBJECT);
        $this->token = Yii::$app->encrypter->encrypt($token);
      
        $this->validate('token');
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function signup()
    {
        $transaction = \Yii::$app->db->beginTransaction();
      
        try {
            $this->generateDbConnect();
            $this->selectHost();
          
            if ($this->save()) {
                $transaction->commit();
                return true;
            }
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
      
        $transaction->rollBack();
        return false;
    }
}
