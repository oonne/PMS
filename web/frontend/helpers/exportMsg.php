<?php

namespace frontend\helpers;

use Yii;

class exportMsg
{
    /**
     * 没有任何数据的正确返回
     */
    public function ok()
    {
        return [
            'Ret' => 0,
        ];
    }

    /**
     * 从配置文件中获取错误信息并构造结构体返回
     */
    public function error($code)
    {
        $errorMsg = Yii::$app->params['errorMsg'];
        $msg = '未知错误';
        if(array_key_exists($code, $errorMsg)){
            $msg = $errorMsg[$code];
        }
        return[
            'Ret' => $code,
            'Data' =>  [
                'errors' => [$msg]
            ],
        ];
    }
}

