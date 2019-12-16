<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use frontend\filters\AuthFilter;

class DonationController extends Controller
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
            'test-get' => ['get'],
            'test-post' => ['post'],
        ];
    }

    /**
     * Test api
     *
     * @return array
     */
    public function actionTestGet($params)
    {
        $verb = Yii::$app->getRequest()->getMethod();
        return [
            'verb' => $verb,
            'params' => $params
        ];
    }

    /**
     * Test api
     *
     * @return array
     */
    public function actionTestPost()
    {
        $params = Yii::$app->request->post();
        $verb = Yii::$app->getRequest()->getMethod();
        return [
            'verb' => $verb,
            'params' => $params
        ];
    }
}