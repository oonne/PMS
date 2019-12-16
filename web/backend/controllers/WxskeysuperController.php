<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use common\models\WxSkey;

class WxskeysuperController extends Controller
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
        $query = WxSkey::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUpdatedTime' => SORT_DESC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteSkey($id)
    {
        $model = WxSkey::findOne($id);

        if (!$model) {
            throw new BadRequestHttpException('请求错误！');
        }

        $transaction = Yii::$app->db->beginTransaction();
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

        return $this->redirect(['index']);
    }
}
