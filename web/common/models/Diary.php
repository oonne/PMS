<?php

namespace common\models;


/**
 * This is the model class for table "{{%diary}}".
 *
 * @property string $uDiaryID
 * @property string $sDate
 * @property string $tDiaryContent
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Diary extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%diary}}';
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
            [['tDiaryContent'], 'required', 'on' => ['creation']],
            [['tDiaryContent'], 'string'],
            [['sDate'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uDiaryID' => 'U Diary ID',
            'sDate' => '日期',
            'tDiaryContent' => '内容',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '最近更新时间',
            'uLastAccountID' => '最后更新帐号',
        ];
    }
}