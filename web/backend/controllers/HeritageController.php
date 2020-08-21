<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use common\models\Config;
use backend\models\ConsumptionSearch;

class HeritageController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionConsumption()
    {
        $config = Config::find()
            ->where(['sConfigKey' => 'LAST_ACCESS'])
            ->one();
        $lastAccess = $config->tConfigValue;

        $searchModel = new ConsumptionSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('consumption', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
            'lastAccess' => $lastAccess,
        ]);
    }
}