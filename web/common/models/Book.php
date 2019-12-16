<?php

namespace common\models;


/**
 * This is the model class for table "{{%book}}".
 *
 * @property string $uBookID
 * @property string $sBookTitle
 * @property string $sIsGood
 * @property string $tSummary
 * @property string $sDate
 * @property string $sCreatedTime
 * @property string $uUpdatedTime
 * @property string $uLastAccountID
 */

class Book extends ActiveRecord
{

    const BOOL_YES = 'Y';
    const BOOL_NO = 'N';

    private static $_isGoodList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%book}}';
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
            [['sBookTitle','sIsGood','sDate'], 'required', 'on' => ['creation']],
            [['sBookTitle'], 'string', 'max' => 32],
            [['sIsGood'], 'in', 'range' => [self::BOOL_YES, self::BOOL_NO]],
            [['sDate'], 'date', 'format' => 'yyyy-MM-dd'],
            [['tSummary'], 'string'],
            [['tSummary'], 'default','value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uBookID' => 'U Book ID',
            'sBookTitle' => '书名',
            'sIsGood' => '是否好书',
            'IsGoodMsg' => '是否好书',
            'tSummary' => '读后感',
            'sDate' => '时间',
            'sCreatedTime' => '创建时间',
            'uUpdatedTime' => '更新时间',
            'uLastAccountID' => '最后更新帐号',
            'dateRange' => '时间',
        ];
    }

    public static function getIsGoodList()
    {
        if (self::$_isGoodList === null) {
            self::$_isGoodList = [
                static::BOOL_YES => '不好',
                static::BOOL_NO => '好书'
            ];
        }

        return self::$_isGoodList;
    }

    public function getIsGoodMsg()
    {
        $list = static::getIsGoodList();

        return $list[$this->sIsGood] ?? null;
    }
}