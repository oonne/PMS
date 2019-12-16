<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use frontend\filters\OptionsFilter;
use frontend\filters\CorsFilter;

class Controller extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
        $behaviors['corsFilter'] = [
            'class' => CorsFilter::className(),
            'cors' => [
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Request-Method' => ['POST', 'GET'],
                'Access-Control-Request-Headers' => ['Content-Type', 'X-Auth-Token'],
                'Origin' => Yii::$app->params['apiOrigin'],
            ]
        ];
        $behaviors['verbFilter'] = [
            'class' => OptionsFilter::className(),
            'actions' => $this->verbs(),
        ];
        return $behaviors;
    }
}
