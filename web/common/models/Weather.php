<?php

namespace common\models;


/**
 * This is the model class for table "{{%weather}}".
 *
 * @property string $uWeatherID
 * @property string $sTime
 * @property string $Station
 * @property string $dTemperature
 * @property string $dHumidity
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Weather extends ActiveRecord
{

    const HOME = 'home';
    const COMPANY = 'company';

    private static $_stationList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weather}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            parent::timestampBehavior()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sTime','Station','dTemperature','dHumidity'], 'required', 'on' => ['creation']],
            [['sTime'], 'date', 'format' => 'yyyy-mm-dd--HH'],
            [['Station'], 'in', 'range' => [self::HOME, self::COMPANY]],
            [['dTemperature', 'dHumidity'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uWeatherID' => 'U Weather ID',
            'sTime' => '时间',
            'Station' => '气象站',
            'dTemperature' => '温度',
            'dHumidity' => '湿度',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }

    public static function getstationList()
    {
        if (self::$_stationList === null) {
            self::$_stationList = [
                static::HOME => '自宅',
                static::COMPANY => '公司'
            ];
        }

        return self::$_stationList;
    }

    public function getstationMsg()
    {
        $list = static::getstationList();

        return $list[$this->Station] ?? null;
    }
}