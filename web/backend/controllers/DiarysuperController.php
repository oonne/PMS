<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use backend\models\DiarySearch;
use common\models\Diary;
use common\models\Recycle;


class DiarysuperController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new DiarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddDiary()
    {
        $model = new Diary();
        $model->setScenario('creation');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', '添加成功！');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', '添加失败。');
                }
            }
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }

    public function actionUpdateDiary($id)
    {
        $model = Diary::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', '保存成功！');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', '更新失败。');
                }
            }
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    public function actionSaveDiary($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Diary::findOne($id);
        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        $form = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post(), '')) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    return [
                        'status' => 'success',
                    ];
                } else {
                    return [
                        'status' => 'fail',
                        'data' => [
                            'message' => '保存出错！'
                        ]
                    ];
                }
            }
        }
    }

    public function actionViewDiary($id)
    {
        $model = Diary::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionDeleteDiary($id)
    {
        $model = Diary::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        $transaction = Yii::$app->db->beginTransaction();
        $recycleContent = $model->sDate ."  \n". $model->tDiaryContent;
        $recycle = new Recycle();
        $recycle->Category = Recycle::CATEGORY_DIARY;
        $recycle->tRecycleContent = $recycleContent;
        if ($recycle->validate()&&$recycle->save(false)) {
            try {
                if (!$model->delete()) {
                    throw new \Exception('删除失败！');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', '删除成功！');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', $e->getMessage());
            }
        } else {
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', '回收失败');
        }

        return $this->redirect(['index']);
    }
}