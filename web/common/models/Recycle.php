<?php

namespace common\models;


/**
 * This is the model class for table "{{%recycle}}".
 *
 * @property string $uRecycleID
 * @property string $Category
 * @property string $tRecycleContent
 * @property string $uCreatedTime
 * @property string $uLastAccountID
 */

class Recycle extends ActiveRecord
{
    const CATEGORY_CONSUMPTION             = 'Consumption';
    const CATEGORY_ESTOVERSPARENTS         = 'EstoversParents';
    const CATEGORY_INCOME                  = 'Income';
    const CATEGORY_DONATION                = 'Donation';
    const CATEGORY_NOTE                    = 'Note';
    const CATEGORY_DIARY                   = 'Diary';
    const CATEGORY_BOOK                    = 'Book';
    const CATEGORY_PASSWORD                = 'Password';
    const CATEGORY_PEDOMETER               = 'Pedometer';
    const CATEGORY_WEATHER                 = 'Weather';
    const CATEGORY_CONFIG                  = 'Config';

    private static $_categoryList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recycle}}';
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
            [['Category', 'tRecycleContent'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uRecycleID' => 'U Recycle ID',
            'Category' => '类型',
            'dateRange' => '删除时间',
            'tRecycleContent' => '关键内容',
            'sCreatedTime' => '删除时间',
            'uLastAccountID' => '删除的帐号id',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getCategoryList()
    {
        if (self::$_categoryList === null) {
            self::$_categoryList = [
                self::CATEGORY_CONSUMPTION       => '个人消费',
                self::CATEGORY_ESTOVERSPARENTS   => '赡养父母',
                self::CATEGORY_INCOME            => '收入',
                self::CATEGORY_DONATION          => '捐款',
                self::CATEGORY_NOTE              => '记事本',
                self::CATEGORY_DIARY             => '日记',
                self::CATEGORY_BOOK              => '读书',
                self::CATEGORY_PASSWORD          => '密码',
                self::CATEGORY_PEDOMETER         => '计步器',
                self::CATEGORY_WEATHER           => '气象站',
                self::CATEGORY_CONFIG            => '配置',
            ];
        }

        return self::$_categoryList;
    }

    public function getCategoryMsg()
    {
        $list = static::getCategoryList();

        return $list[$this->Category] ?? null;
    }
}