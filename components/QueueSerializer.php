<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use yii\base\Object;

/**
 * Class PhpSerializer
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class QueueSerializer extends Object implements \yii\queue\serializers\Serializer
{
    private $_namespace = '\\app\\jobs\\';
    
    /**
     * @inheritdoc
     */
    public function serialize($job)
    {
        $data = is_object($job) ? (array)$job : $job;
      
        if (!isset($data['command']) || !$data['command']) {
          $data['command'] = \yii\helpers\StringHelper::basename(get_class($job));
        }

        return serialize($data);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (is_array($data) && isset($data['command'])) {
          if (isset($data['namespace']) && $data['namespace']) {
            $this->_namespace = $data['namespace'];
            unset ($data['namespace']);
          }
          
          $data['class'] = $this->_namespace . $data['command'];
          unset ($data['command']);
          
          if (class_exists ($data['class'])) {
              return \Yii::createObject($data);
          }
        }

        return unserialize($serialized);
    }
}