<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use frontend\filters\AuthFilter;
use frontend\helpers\exportMsg;
use common\models\Diary;
use common\models\Recycle;

class DiaryController extends Controller
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
            $query = Diary::find()
                ->where(['like', 'tDiaryContent', $word])
                ->select(['uDiaryID', 'sDate', 'tDiaryContent']);
        } else {
            $query = Diary::find()
                ->select(['uDiaryID', 'sDate', 'tDiaryContent']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC, 'uUpdatedTime' => SORT_DESC]]
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
        $model = new Diary();
        $model->setScenario('creation');

        if ($model->load(Yii::$app->request->post(), '')) {
            if ($model->validate()) {
                $model->uLastAccountID = Yii::$app->user->id;
                if ($model->save(false)) {
                    $data = $model->toArray(['uDiaryID', 'sDate', 'tDiaryContent']);
                    return [
                        'Ret' => 0,
                        'Data' => $data,
                    ];
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
        $diary = Yii::$app->request->post();
        $id = $diary['uDiaryID'];
        $model = Diary::findOne($id);

        if (!$model) {
            return exportMsg::error('101000');
        }

        if ($model->load($diary, '') && $model->validate()) {
            $model->uLastAccountID = Yii::$app->user->id;
            if ($model->save(false)) {
                $data = $model->toArray(['uDiaryID', 'sDate', 'tDiaryContent']);
                return [
                    'Ret' => 0,
                    'Data' => $data,
                ];
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
        $diary = Yii::$app->request->post();
        $id = $diary['uDiaryID'];
        $model = Diary::findOne($id);

        if (!$model) {
            return exportMsg::error('101000');
        }

        $transaction = Yii::$app->db->beginTransaction();
        $recycleContent = $model->sDate ."  \n". $model->tDiaryContent;
        $recycle = new Recycle();
        $recycle->Category = Recycle::CATEGORY_DIARY;
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