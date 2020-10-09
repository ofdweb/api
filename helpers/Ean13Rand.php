<?php


namespace common\helpers;


use common\components\template\Ean13Interface;
use yii\base\InvalidValueException;

/**
 * Базовый объект создания EAN
 *
 * Class Ean13Base
 * @package common\helpers
 */
class Ean13Rand implements Ean13Interface
{
    /**
     * @var int
     */
    private $_number;

    /**
     * @inheritDoc
     */
    public function __construct(int $number = null)
    {
        $number = (string)$number;
        if (strlen($number) > 13) {
            throw new InvalidValueException("Длина строки содержит более 12 символов.");
        }

        if (empty($number) === false) {
            $number .= '0';
        }

        $rand = (string)mt_rand(10000000, 99999999);
        $this->_number = str_pad($number, 8, $rand, STR_PAD_RIGHT);
    }

    /**
     * Строка для кодирования
     * @return int
     */
    public function getUniqValue(): int
    {
        return (int)$this->_number;
    }

    /**
     * Результат
     * @param string $value
     * @return string
     */
    public function generate(string $value): string
    {
        return $value;
    }
}