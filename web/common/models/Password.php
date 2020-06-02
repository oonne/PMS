<?php

namespace common\models;


/**
 * This is the model class for table "{{%password}}".
 *
 * @property string $uPasswordID
 * @property string $sPasswordItem
 * @property string $sUserName
 * @property string $sPassword
 * @property string $tRemark
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Password extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%password}}';
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
            [['sPasswordItem','sUserName','sPassword'], 'required', 'on' => ['creation']],
            [['sPasswordItem','sUserName'], 'string', 'max' => 32],
            [['sPassword'], 'string', 'max' => 256],
            [['tRemark'], 'string'],
            [['tRemark'], 'default','value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uPasswordID' => 'U Password ID',
            'sPasswordItem' => '密码项',
            'sUserName' => '用户名',
            'sPassword' => '密码',
            'tRemark' => '其他信息',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
        ];
    }

}