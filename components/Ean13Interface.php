<?php


namespace common\components\template;


use yii\base\BaseObject;

/**
 * Интерфейс формирования номера платежа
 *
 * Interface Ean13Interface
 * @package common\components\template
 */
interface Ean13Interface
{
    /**
     * Строка для кодирования
     * @return int
     */
    public function getUniqValue(): int;

    /**
     * Результат
     * @param string $value
     * @return string
     */
    public function generate(string $value): string;
}