<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\EstoversParents;

class EstoversParentsSearch extends EstoversParents
{
    public $dateRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dMoney', 'sEstoversItem', 'sRemark', 'dateRange'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = EstoversParents::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC]]
        ]);

        $data = [];

        if (!($this->load($params) && $this->validate())) {
            $data['dataProvider'] = $dataProvider;
            $data['summary'] = $this->_summary();
            return $data;
        }

        QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2){
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'sEstoversItem', $this->sEstoversItem])
              ->andFilterWhere(['like', 'sRemark', $this->sRemark]);

        $data['dataProvider'] = $dataProvider;
        $data['summary'] = $this->_summary();
        return $data;
    }

    public function _summary()
    {
        $query = EstoversParents::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([EstoversParents::tableName()]);

        if ($this->dMoney) {
            QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);
        }

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2) {
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }

        if ($this->sEstoversItem) {
            $query->andWhere(['like', 'sEstoversItem', $this->sEstoversItem]);
        }
        if ($this->sRemark) {
            $query->andWhere(['like', 'sRemark', $this->sRemark]);
        }                

        $data = $query->createCommand()->queryOne();

        return $data['summary'];
    }
}
