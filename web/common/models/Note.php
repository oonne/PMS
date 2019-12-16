<?php

namespace common\models;


/**
 * This is the model class for table "{{%note}}".
 *
 * @property string $uNoteID
 * @property string $sNoteTitle
 * @property string $tNoteContent
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Note extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%note}}';
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
            [['sNoteTitle','tNoteContent'], 'required', 'on' => ['creation']],
            [['sNoteTitle'], 'string', 'max' => 32],
            [['tNoteContent'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uNoteID' => 'U Note ID',
            'sNoteTitle' => '标题',
            'tNoteContent' => '内容',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '最近更新时间',
            'uLastAccountID' => '最后更新帐号',
            'updatedTimeRange' => '更新时间',
        ];
    }
}