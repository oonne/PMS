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
use backend\models\NoteSearch;
use common\models\Note;

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

    // 消费
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

    // 赡养父母
    public function actionEstoversparents()
    {
        $searchModel = new EstoversParentsSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('estoversparents', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
        ]);
    }

    // 收入
    public function actionIncome()
    {
        $searchModel = new IncomeSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('income', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
        ]);
    }

    // 记事本
    public function actionNoteIndex()
    {
        $searchModel = new NoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('note-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNoteView($id)
    {
        $model = Note::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        return $this->render('note-view', [
            'model' => $model
        ]);
    }
}