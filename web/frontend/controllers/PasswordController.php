<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use frontend\filters\AuthFilter;
use frontend\helpers\exportMsg;
use common\models\Password;
use common\models\Recycle;

class PasswordController extends Controller
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
            'index' => ['get'],
            'add' => ['post'],
            'update' => ['post'],
            'delete' => ['post'],
        ];
    }

    public function actionIndex($word='')
    {
        if ($word) {
            $query = Password::find()
                ->where(['like', 'sPasswordItem', $word])
                ->orWhere(['like', 'sUserName', $word])
                ->orWhere(['like', 'tRemark', $word])
                ->select(['uPasswordID', 'sPasswordItem', 'sUserName', 'sPassword', 'tRemark']);
        } else {
            $query = Password::find()
                ->select(['uPasswordID', 'sPasswordItem', 'sUserName', 'sPassword', 'tRemark']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUpdatedTime' => SORT_DESC]]
        ]);

        $data = $dataProvider->getModels();
        $meta = [
            'totalCount' => $dataProvider->pagination->totalCount,
            'pageCount' => $dataProvider->pagination->getPageCount(),
            'currentPage' => $dataProvider->pagination->getPage() + 1,
            'perPage' => $dataProvider->pagination->getPageSize(),
        ];

        return [
            'Ret' => 0,
            'Data' => $data,
            'Meta' => $meta,
        ];
    }

    public function actionAdd()
    {
        $model = new Password();
        $model->setScenario('creation');

        if ($model->load(Yii::$app->request->post(), '')) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    return exportMsg::ok();
                } else {
                    return exportMsg::error('101001');
                }
            } else {
                return [
                    'Ret' => '101001',
                    'Data' => [
                        'errors' => $model->getFirstErrors()
                    ]
                ];                
            }
        }

        return exportMsg::error('101004');
    }

    public function actionUpdate()
    {
        $note = Yii::$app->request->post();
        $id = $note['uPasswordID'];
        $model = Password::findOne($id);

        if (!$model) {
            return exportMsg::error('101000');
        }

        if ($model->load($note, '') && $model->validate()) {
            $model->uLastAccountID = Yii::$app->user->id;
            if ($model->save(false)) {
                return exportMsg::ok();
            } else {
                return exportMsg::error('101003');
            }
        }

        return [
            'Ret' => '101003',
            'Data' => [
                'errors' => $model->getFirstErrors()
            ]
        ];
    }

    public function actionDelete()
    {
        $note = Yii::$app->request->post();
        $id = $note['uPasswordID'];
        $model = Password::findOne($id);

        if (!$model) {
            return exportMsg::error('101000');
        }

        $transaction = Yii::$app->db->beginTransaction();
        $recycleContent = $model->sPasswordItem . "  \n用户名：" . $model->sUserName . "  \n密码：" . $model->sPassword . "  \n" . $model->tRemark;
        $recycle = new Recycle();
        $recycle->Category = Recycle::CATEGORY_PASSWORD;
        $recycle->tRecycleContent = $recycleContent;
        if($recycle->validate()&&$recycle->save(false)){
            try {
                if (!$model->delete()) {
                    return exportMsg::error('101002');
                }
                $transaction->commit();
                return exportMsg::ok();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return [
                    'Ret' => '101002',
                    'Data' => [
                        'errors' => [$e->getMessage()]
                    ]
                ];
            }
        }else{
            $transaction->rollBack();
            return exportMsg::error('101005');
        }
    }
}