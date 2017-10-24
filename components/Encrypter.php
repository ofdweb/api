<?php
namespace app\components;


class Encrypter extends \nickcv\encrypter\components\Encrypter
{
    private $_useBase64Encoding = true;
  
    /**
     * @inheritdoc
     */
    public function encrypt($string)
    {
        $result = parent::encrypt($string);
      
        if ($this->_useBase64Encoding) {
            return substr($result, 0, -2);
        }
      
        return $result;
    }
  
    /**
     * @inheritdoc
     */
    public function decrypt($string)
    {
        if ($this->_useBase64Encoding) {
            $string .= '==';
        }
      
        return parent::decrypt($string);
    }
}