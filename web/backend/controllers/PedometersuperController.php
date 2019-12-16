<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use common\models\Pedometer;
use backend\models\PedometerSearch;
use common\models\Recycle;

class PedometersuperController extends Controller
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
        $searchModel = new PedometerSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
        ]);
    }

    public function actionAddPedometer()
    {
        $model = new Pedometer();
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

    public function actionUpdatePedometer($id)
    {
        $model = Pedometer::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', '更新成功！');
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

    public function actionDeletePedometer($id)
    {
        $model = Pedometer::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        $transaction = Yii::$app->db->beginTransaction();
        $recycleContent = "  \n日期：". $model->sDate ."  \n步数：". $model->uStep;
        $recycle = new Recycle();
        $recycle->Category = Recycle::CATEGORY_PEDOMETER;
        $recycle->tRecycleContent = $recycleContent;
        if($recycle->validate()&&$recycle->save(false)){
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
        }else{
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', '回收失败');
        }

        return $this->redirect(['index']);
    }
}
