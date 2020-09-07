<?php

namespace common\models;


/**
 * This is the model class for table "{{%position}}".
 *
 * @property string $uPositionID
 * @property string $dLongitude
 * @property string $dLatitude
 * @property string $sWifi
 * @property string $sTime
 * @property string $Place
 * @property string $sRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Pedometer extends ActiveRecord
{
    const PLACE_HOME = 'home';
    const PLACE_COMPANY = 'company';
    const PLACE_METRO = 'metro';
    const PLACE_CAR = 'CAR';
    const PLACE_OTHER = 'other';

    private static $_placeList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position}}';
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
            [['sTime'], 'required', 'on' => ['creation']],
            [['dLongitude', 'dLongitude'], 'number'],
            [['sTime'], 'date'],
            [['Place'], 'in', 'range' => [self::PLACE_HOME, self::PLACE_COMPANY, self::PLACE_METRO, self::PLACE_OTHER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uPositionID' => 'U Position ID',
            'dLongitude' => '经度',
            'dLatitude' => '纬度',
            'sWifi' => 'wifi',
            'sTime' => '时间',
            'Place' => '地点',
            'sRemark' => '备注',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }

    public static function getPlaceList()
    {
        if (self::$_placeList === null) {
            self::$_placeList = [
                static::PLACE_HOME => '家',
                static::PLACE_COMPANY => '公司',
                static::PLACE_METRO => '地铁',
                static::PLACE_CAR => '开车',
                static::PLACE_OTHER => '其他'
            ];
        }

        return self::$_placeList;
    }

    public function getPlaceMsg()
    {
        $list = static::getPlaceList();

        return $list[$this->Place] ?? null;
    }
}