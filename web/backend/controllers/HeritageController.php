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
use backend\models\PedometerSearch;
use common\models\Note;
use backend\models\NoteSearch;
use common\models\Password;
use backend\models\PasswordSearch;
use common\models\Diary;
use backend\models\DiarySearch;
use common\models\Book;
use backend\models\BookSearch;

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

    // 密码
    public function actionPasswordIndex()
    {
        $searchModel = new PasswordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('password-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPasswordView($id)
    {
        $model = Password::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        return $this->render('password-view', [
            'model' => $model
        ]);
    }

    // 日记
    public function actionDiaryIndex()
    {
        $config = Config::find()
            ->where(['sConfigKey' => 'LAST_ACCESS'])
            ->one();
        $lastAccess = $config->tConfigValue;

        $searchModel = new DiarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('diary-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lastAccess' => $lastAccess,
        ]);
    }

    public function actionDiaryView($id)
    {
        $model = Diary::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        return $this->render('diary-view', [
            'model' => $model
        ]);
    }

    // 读书
    public function actionBookIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('book-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBookView($id)
    {
        $model = Book::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        return $this->render('book-view', [
            'model' => $model
        ]);
    }

    // 计步器
    public function actionPedometer()
    {
        $searchModel = new PedometerSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pedometer', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
        ]);
    }
}