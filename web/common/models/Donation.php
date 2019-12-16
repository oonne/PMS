<?php

namespace common\models;


/**
 * This is the model class for table "{{%donation}}".
 *
 * @property string $uDonationID
 * @property string $sDonationItem
 * @property string $dMoney
 * @property string $sDate
 * @property string $sRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Donation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%donation}}';
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
            [['sDonationItem','dMoney','sDate'], 'required', 'on' => ['creation']],
            [['sDonationItem'], 'string', 'max' => 32],
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
            'uDonationID' => 'U Donation ID',
            'sDonationItem' => '项目',
            'dMoney' => '金额',
            'sDate' => '时间',
            'sRemark' => '备注',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
        ];
    }
}