<?php

namespace common\models;


/**
 * This is the model class for table "{{%pedometer}}".
 *
 * @property string $uPedometerID
 * @property string $sDate
 * @property string $uStep
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Pedometer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pedometer}}';
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
            [['uStep','sDate'], 'required', 'on' => ['creation']],
            [['uStep'], 'integer'],
            [['sDate'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uPedometerID' => 'U Pedometer ID',
            'uStep' => '步数',
            'sDate' => '日期',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }
}