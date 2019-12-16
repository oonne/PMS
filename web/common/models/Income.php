<?php

namespace common\models;


/**
 * This is the model class for table "{{%income}}".
 *
 * @property string $uIncomeID
 * @property string $sIncomeItem
 * @property string $dMoney
 * @property string $sDate
 * @property string $sRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Income extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%income}}';
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
            [['sIncomeItem','dMoney','sDate'], 'required', 'on' => ['creation']],
            [['sIncomeItem'], 'string', 'max' => 32],
            [['dMoney'], 'integer'],
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
            'uIncomeID' => 'U Income ID',
            'sIncomeItem' => '项目',
            'dMoney' => '金额',
            'sDate' => '时间',
            'sRemark' => '备注',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }
}