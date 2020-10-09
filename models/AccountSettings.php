<?php
/**
 * Created by PhpStorm.
 * User: itily
 * Date: 09.10.2020
 * Time: 15:25
 */

namespace app\models;


use yii2mod\settings\models\SettingModel;

/**
 * Class AccountSettings
 * @package app\models
 */
class AccountSettings extends SettingModel
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%account_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules[3][0][0]);

        return array_merge($rules, [
            [['section'], 'integer']
        ]);
    }

    public function fields()
    {
        return [
            'key' => 'key',
            'value'
        ];
    }


    /**
     * @param $section
     * @param $key
     * @return \frontend\modules\account\models\AccountSettings|null
     */
    public static function getSetting($section, $key)
    {
        $model = static::find()
            ->select(['section', 'key', 'value'])
            ->where(['section' => $section, 'key' => $key])
            ->active()
            ->one();

        if (!empty($model)) {
            return $model;
        }

        return;
    }

    /**
     * Настройки аккаунта
     * @param integer $section
     * @return array
     */
    public static function getAccountSettings($section): array
    {
        return static::find()
            ->select(['type', 'section', 'key', 'value'])
            ->where(['section' => $section])
            ->active()
            ->all();
    }
}
{

}