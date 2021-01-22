<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use frontend\filters\AuthFilter;
use frontend\helpers\exportMsg;
use common\models\Config;

class ConfigController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => AuthFilter::className()
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
            'update' => ['post'],
        ];
    }

    public function actionUpdate()
    {
        $config = Yii::$app->request->post();
        $key = $config['sConfigKey'];
        $model = Config::find()
                    ->where(['sConfigKey' => $key])
                    ->one();

        if (!$model) {
            return exportMsg::error('101000');
        }

        if ($model->load($config, '') && $model->validate()) {
            $model->uLastAccountID = Yii::$app->user->id;
            if ($model->save(false)) {
                $data = $model->toArray(['uConfigID', 'sConfigName', 'sConfigKey', 'tConfigValue']);
                return [
                    'Ret' => 0,
                    'Data' => $data,
                ];
            } else {
                return exportMsg::error('101003');
            }
        }

        return [
            'Ret' => '101003',
            'Data' => [
                'errors' => $model->getFirstErrors()
            ]
        ];
    }
}