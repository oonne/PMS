<?php

namespace common\models;


/**
 * This is the model class for table "{{%estovers_parents}}".
 *
 * @property string $uEstoversID
 * @property string $sEstoversItem
 * @property string $dMoney
 * @property string $sDate
 * @property string $sRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class EstoversParents extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%estovers_parents}}';
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
            [['sEstoversItem','dMoney','sDate'], 'required', 'on' => ['creation']],
            [['sEstoversItem'], 'string', 'max' => 32],
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
            'uEstoversID' => 'U Estovers ID',
            'sEstoversItem' => '项目',
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