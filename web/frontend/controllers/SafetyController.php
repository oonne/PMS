<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use frontend\filters\AuthFilter;
use frontend\helpers\exportMsg;
use common\models\Config;

class SafetyController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => AuthFilter::className(),
            'only' => ['refresh']
        ];
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'refresh' => ['post'],
            'check' => ['get'],
        ];
    }

    /*
     * 刷新最后登录时间
     */ 
    public function actionRefresh()
    {
        $model = Config::find()
                    ->where(['sConfigKey' => 'LAST_ACCESS'])
                    ->one();

        if (!$model) {
            return exportMsg::error('101000');
        }

        $model->tConfigValue = 'ALIVE '.date('Y-m-d H:i:s', time());
        $model->uLastAccountID = Yii::$app->user->id;
        if ($model->save(false)) {
            return exportMsg::ok();
        } else {
            return exportMsg::error('101003');
        }
    }

    /*
     * 检查最后登录时间
     * 如果登录时间超过3天，则设置为 DANGER （危险）
     * 如果登录时间超过20天，则设置为 DEAD （死亡）
     */ 
    public function actionCheck()
    {
        $model = Config::find()
                    ->where(['sConfigKey' => 'LAST_ACCESS'])
                    ->one();

        if (!$model) {
            return exportMsg::error('101000');
        }

        $lastAccess = strtotime($model->uUpdatedTime);
        $now = time();

        if ($now-$lastAccess>3600*24*20) {
            $model->tConfigValue = 'DEAD';
            if ($model->save(false)) {
                return 'DEAD';
            } else {
                return exportMsg::error('101003');
            }           
        } if ($now-$lastAccess>3600*24*3) {
            $model->tConfigValue = 'DANGER';
            if ($model->save(false)) {
                return 'DANGER';
            } else {
                return exportMsg::error('101003');
            }           
        } else {
            return $now - $lastAccess;
        }
    }
}