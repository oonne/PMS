<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wx_skey}}".
 *
 * @property string $uSkeyID
 * @property string $sOpenID
 * @property string $sSessionKey
 * @property string $sWxToken
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 */

class WxSkey extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_skey}}';
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
            [['sOpenID','sSessionKey'], 'required', 'on' => ['creation']],
            [['sOpenID','sSessionKey'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uSkeyID' => 'U Skey ID',
            'sOpenID' => 'OpenID',
            'sSessionKey' => 'SessionKey',
            'sWxToken' => '自定义登录态',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'dateRange' => '时间',
        ];
    }

    /**
     * Generates access token
     */
    public function generateWxToken()
    {
        $this->sWxToken = Yii::$app->security->generateRandomString();
    }
}