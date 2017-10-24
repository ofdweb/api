<?php
namespace app\components;
 
use Yii;
use yii\di\Instance;
use yii\di\ServiceLocator;
 
class DynamicServiceLocator extends ServiceLocator
{
    /**
     * @var ActiveIdInterface
     */
    public $activeId;
 
    private $forcedId;
 
    public function init()
    {
        $this->activeId = Instance::ensure($this->activeId, 'app\components\ActiveIdInterface');
        parent::init();
    }
 
    public function get($componentId, $id = null, $throwException = true)
    {
        $serviceId = $this->generateServiceId($componentId, $id);
        if (!parent::has($serviceId)) {
            parent::set($serviceId, $this->buildDefinition($componentId, $id));
        }
        return parent::get($serviceId, $throwException);
    }
 
    public function clear($componentId, $id)
    {
        parent::clear($componentId);
        parent::clear($this->generateServiceId($componentId, $id));
    }
 
    public function doWith($id, callable $function)
    {
        $oldId = $this->getCurrentId(null);
        $this->forcedId = $id;
        $result = call_user_func($function, $this);
        $this->forcedId = $oldId;
        return $result;
    }
 
    private function generateServiceId($componentId, $id)
    {
        return 'dynamic_' . $componentId . '_' . $this->getCurrentId($id);
    }
 
    private function buildDefinition($componentId, $id)
    {
        $definitions = $this->getComponents();
        if (!array_key_exists($componentId, $definitions)) {
            return null;
        };
        $definition = $definitions[$componentId];
        if (is_array($definition)) {
            $currentId = $this->getCurrentId($id);
            array_walk_recursive(
                $definition,
                function (&$value) use ($currentId) {
                    if (is_string($value)) {
                        $value = str_replace('{id}', $currentId, $value);
                    }
                }
            );
            return $definition;
        } elseif (is_object($definition) && $definition instanceof \Closure) {
            return Yii::$container->invoke($definition, [$this->getCurrentId($id)]);
        } else {
            return $definition;
        }
    }
 
    private function getCurrentId($id)
    {
        if (!empty($id)) {
            return $id;
        }
        if (!empty($this->forcedId)) {
            return $this->forcedId;
        }
        return $this->activeId->get();
    }
}