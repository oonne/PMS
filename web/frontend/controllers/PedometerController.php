<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use frontend\filters\AuthFilter;
use frontend\helpers\exportMsg;
use common\models\Pedometer;

class PedometerController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => AuthFilter::className(),
            'only' => ['update']
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
            'all' => ['get'],
            'latest' => ['get'],
            'update' => ['post'],
        ];
    }

    public function actionAll()
    {
        $query = Pedometer::find()
            ->select(['uPedometerID', 'uStep', 'sDate'])
            ->orderBy(['sDate' => SORT_DESC]);
        $pedometer = $query->all();

        return [
            'Ret' => 0,
            'Data' => $pedometer
        ];
    }

    public function actionLatest()
    {
        $query = Pedometer::find()
            ->select(['uPedometerID', 'uStep', 'sDate'])
            ->orderBy(['sDate' => SORT_DESC]);
        $monthly = $query->limit(30)->all();
        $avg = round($query->average('uStep'));

        return [
            'Ret' => 0,
            'Data' => [
                'monthly' => $monthly,
                'avg' => $avg,
            ]
        ];
    }

    public function actionUpdate()
    {
        $params = Yii::$app->request->post();
        $stepInfoList = $params['stepInfoList'];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($stepInfoList as $key => $stepInfo) {
                $model = Pedometer::findOne(['sDate' => $stepInfo['date']]);

                if (!$model) {
                    $model = new Pedometer();
                    $model->setScenario('creation');
                }

                $stepData = [
                    'uStep' => $stepInfo['steps'],
                    'sDate' => $stepInfo['date']
                ];

                if ($model->load($stepData, '') && $model->validate()) {
                    $model->uLastAccountID = Yii::$app->user->id;
                    $model->save(false);
                }
            }

            $transaction->commit();
            return exportMsg::ok();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return exportMsg::error('101003');
        }

    }
}