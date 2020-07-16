<?php

namespace common\models;


/**
 * This is the model class for table "{{%config}}".
 *
 * @property string $uConfigID
 * @property string $sConfigName
 * @property string $sConfigKey
 * @property string $tConfigValue
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Config extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
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
            [['sConfigName','sConfigKey','tConfigValue'], 'required', 'on' => ['creation']],
            [['sConfigName'], 'string', 'max' => 128],
            [['sConfigKey'], 'string', 'max' => 32],
            [['tConfigValue'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uConfigID' => 'Config ID',
            'sConfigName' => '名称',
            'sConfigKey' => 'Key',
            'tConfigValue' => 'Value',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '最近更新时间',
            'uLastAccountID' => '最后更新帐号',
        ];
    }
}