<?php

namespace common\models;


/**
 * This is the model class for table "{{%consumption}}".
 *
 * @property string $uConsumptionID
 * @property string $sConsumptionItem
 * @property string $Category
 * @property string $dMoney
 * @property string $sDate
 * @property string $sRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Consumption extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consumption}}';
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
            [['sConsumptionItem','dMoney','sDate','Category'], 'required', 'on' => ['creation']],
            [['sConsumptionItem'], 'string', 'max' => 32],
            [['dMoney'], 'number'],
            
            [['Category'], 'string'],

            [['sDate'], 'date', 'format' => 'yyyy-MM-dd'],
            [['sRemark'], 'string', 'max' => 128],
            [['sRemark'], 'default','value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uConsumptionID' => 'U Consumption ID',
            'sConsumptionItem' => '项目',
            'Category' => '类型',
            'dMoney' => '金额',
            'sDate' => '时间',
            'sRemark' => '备注',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }

    public function getCategoryMsg()
    {
        $list = ConsumptionCategory::getCategoryList();

        return $list[$this->Category] ?? null;
    }
}