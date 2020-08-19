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
        $key = $config['sConfigKey'];
        $model = Config::find()
                    ->where(['sConfigKey' => 'LAST_ACCESS'])
                    ->one();

        if (!$model) {
            return exportMsg::error('101000');
        }

        $model->tConfigValue = 'ALIVE-'.time();
        $model->uLastAccountID = Yii::$app->user->id;
        if ($model->save(false)) {
            return exportMsg::ok();
        } else {
            return exportMsg::error('101003');
        }
    }

    /*
     * 检查最后登录时间
     * 如果登录时间超过72小时，则设置为 DANGER （危险）
     * 如果登录时间超过20天，则设置为 DEAD （死亡）
     */ 
    public function actionCheck()
    {
        $key = $config['sConfigKey'];
        $model = Config::find()
                    ->where(['sConfigKey' => 'LAST_ACCESS'])
                    ->one();

        if (!$model) {
            return exportMsg::error('101000');
        }

        return $model->toArray();
    }
}