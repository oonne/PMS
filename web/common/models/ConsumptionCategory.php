<?php

namespace common\models;

class ConsumptionCategory extends \yii\db\ActiveRecord
{
    const CATEGORY_FOOD             = 'food';
    const CATEGORY_LIVING           = 'living';
    const CATEGORY_TRAVELLING       = 'travelling';
    const CATEGORY_APPLIANCE        = 'appliance';
    const CATEGORY_ENTERTANIMENT    = 'entertaniment';
    const CATEGORY_SELFIMAGE        = 'selfimage';
    const CATEGORY_SELFIMPROVEMENT  = 'selfimprovement';
    const CATEGORY_RECREATION       = 'recreation';
    const CATEGORY_LOSSES           = 'losses';

    private static $_categoryList;

    public static function getCategoryList()
    {
        if (self::$_categoryList === null) {
            self::$_categoryList = [
                self::CATEGORY_FOOD             => '食物',
                self::CATEGORY_LIVING           => '居住',
                self::CATEGORY_TRAVELLING       => '差旅',
                self::CATEGORY_APPLIANCE        => '物品',
                self::CATEGORY_ENTERTANIMENT    => '应酬',
                self::CATEGORY_SELFIMAGE        => '个人形象',
                self::CATEGORY_SELFIMPROVEMENT  => '自我提升',
                self::CATEGORY_RECREATION       => '娱乐',
                self::CATEGORY_LOSSES           => '意外损失',
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
