<?php

namespace common\models;

class RoleModule extends \yii\db\ActiveRecord
{
    const ROLE_JAY         = 'jay';           // 超级管理员

    private static $_roleList;

    public static function getRoleList()
    {
        if (self::$_roleList === null) {
            self::$_roleList = [
                self::ROLE_JAY          => '加一',
            ];
        }

        return self::$_roleList;
    }

    public function getRoleMsg()
    {
        $list = static::getRoleList();

        return $list[$this->RoleType] ?? null;
    }
}
