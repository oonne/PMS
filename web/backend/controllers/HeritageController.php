<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use common\models\Config;
use backend\models\ConsumptionSearch;
use backend\models\EstoversParentsSearch;
use backend\models\IncomeSearch;

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

    public function actionEstoversparents()
    {
        $config = Config::find()
            ->where(['sConfigKey' => 'LAST_ACCESS'])
            ->one();
        $lastAccess = $config->tConfigValue;

        $searchModel = new EstoversParentsSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('estoversparents', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
            'lastAccess' => $lastAccess,
        ]);
    }

    public function actionIncome()
    {
        $config = Config::find()
            ->where(['sConfigKey' => 'LAST_ACCESS'])
            ->one();
        $lastAccess = $config->tConfigValue;

        $searchModel = new IncomeSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('income', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
            'lastAccess' => $lastAccess,
        ]);
    }
}