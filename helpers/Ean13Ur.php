<?php


namespace common\helpers;


use common\components\template\Ean13Interface;

/**
 * Ean13 счета на оплату комиссии
 *
 * Class Ean13Ur
 * @package common\modules\payment\helpers
 */
class Ean13Ur implements Ean13Interface
{
    private $_data;

    /**
     * @inheritDoc
     */
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    /**
     * Строка для кодирования
     * @return int
     */
    public function getUniqValue(): int
    {
        $value = array_shift($this->_data);
        $value = (string)$value . "00" . (string)rand(100, 999);

        return (int)$value;
    }

    /**
     * @param string $value
     * @return string
     */
    public function generate(string $value): string
    {
        array_unshift($this->_data, $value);
        $data = implode('-', $this->_data);

        return "UR-{$data}-0";
    }

    /**
     * Поиск номера
     * @param string $value
     * @return string|null
     */
    public static function parse(string $value)
    {
        $matches = null;

        $pattern = '/UR-*(.+?-+.+?)-+0/is';
        preg_match($pattern, $value, $matches);

        if (isset($matches[1]) === false || !$matches[1]) {
            return null;
        }

        $patterns = ['/-{2,}/', '/\s+/'];
        $replacements = ['-', ''];
        $env = preg_replace($patterns, $replacements, $matches[1]);

        return trim($env);
    }
}