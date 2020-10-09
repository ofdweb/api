<?php


namespace common\helpers;

use common\components\template\Ean13Interface;


/**
 * Хелпер ean13
 * Class Ean13Helper
 * @package yii\helpers
 */
class Ean13Helper
{
    private $_entity;

    /**
     * @inheritDoc
     */
    public function __construct(Ean13Interface $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @param Ean13Interface $entity
     * @return Ean13Helper
     */
    public static function instance(Ean13Interface $entity)
    {
        return new self($entity);
    }

    /**
     * Кодируем значение
     * @param int $value
     * @return string
     */
    public static function encode(int $value)
    {
        $length = (string)strlen($value);
        $value = strrev($value);

        $string = str_pad("{$length}{$value}", 12, 0, STR_PAD_RIGHT);

        $even = [];
        $odd = [];

        foreach (str_split($string) as $k => $v) {
            if ($k % 2 === 0) {
                $even[] = (int)$v;
            } else {
                $odd[] = (int)$v;
            }
        }

        $sum = (array_sum($even) * 3 + array_sum($odd)) / 10;
        $sum = ceil($sum) * 10 - $sum * 10;

        return "{$string}{$sum}";
    }

    /**
     * Генерим ean13
     * @return string
     */
    public function generate()
    {
        $uniqId = $this->_entity->getUniqValue();
        $result = self::encode($uniqId);

        return $this->_entity->generate($result);
    }
}