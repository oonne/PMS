<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\db\Query;
use common\models\Consumption;
use common\models\ConsumptionCategory;
use common\models\Recycle;
use backend\models\ConsumptionSearch;

class ConsumptionsuperController extends Controller
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
        $searchModel = new ConsumptionSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $data['dataProvider'],
            'summary' => $data['summary'],
        ]);
    }

    public function actionAddConsumption()
    {
        $model = new Consumption();
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

    public function actionUpdateConsumption($id)
    {
        $model = Consumption::findOne($id);

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

    public function actionDeleteConsumption($id)
    {
        $model = Consumption::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        $transaction = Yii::$app->db->beginTransaction();
        $recycleContent = $model->sConsumptionItem ."  \n时间：". $model->sDate ."  \n金额：". $model->dMoney ."  \n". $model->sRemark;
        $recycle = new Recycle();
        $recycle->Category = Recycle::CATEGORY_CONSUMPTION;
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


    // 月度统计
    public function actionSummaryMonth()
    {        
        $query = (new Query())->select([
                'month' => "DATE_FORMAT(T0.sDate, '%Y-%m')",
                'money' => 'SUM(T0.dmoney)'])
            ->from(['T0' => Consumption::tableName()])
            ->groupBy(["DATE_FORMAT(T0.sDate, '%Y-%m')"]);

        $data = $query->createCommand()->queryAll();

        $month = [];
        $money = [];
        foreach ($data as $item) {
            array_push($month, $item['month']);
            array_push($money, $item['money']);
        }

        return $this->render('summary-month', [  
            'month' => $month,
            'money' => $money,
        ]);
    }

    // 消费比例
    public function actionSummaryRate()
    {        
        $categoryList = ConsumptionCategory::getCategoryList();

        $query = (new Query())->select([
                'category' => 'T0.Category',
                'value' => 'SUM(T0.dmoney)'])
            ->from(['T0' => Consumption::tableName()])
            ->groupBy(['T0.Category']);

        $queryResult = $query->createCommand()->queryAll();

        $data = [];
        foreach ($categoryList as $categoryKey => $category) {
            foreach ($queryResult as $item)  
            {
                if($item['category'] == $categoryKey){
                    array_push($data, [
                        'name' => $category,
                        'value' => $item['value']
                    ]);
                }
            }
        }

        return $this->render('summary-rate', [  
            'data' => $data
        ]);
    }
}
