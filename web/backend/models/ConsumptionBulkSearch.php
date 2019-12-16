<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\ConsumptionBulk;

class ConsumptionBulkSearch extends ConsumptionBulk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dMoney', 'sDate', 'sConsumptionItem', 'sRemark'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = ConsumptionBulk::find();

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
        
        // grid filtering conditions
        $query->andFilterWhere(['like', 'sConsumptionItem', $this->sConsumptionItem])
              ->andFilterWhere(['like', 'sRemark', $this->sRemark])
              ->andFilterWhere(['sDate'=> $this->sDate]);

        $data['dataProvider'] = $dataProvider;
        $data['summary'] = $this->_summary();
        return $data;
    }

    public function _summary()
    {
        $query = ConsumptionBulk::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([ConsumptionBulk::tableName()]);

        if ($this->dMoney) {
            QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);
        }

        if ($this->sConsumptionItem) {
            $query->andWhere(['like', 'sConsumptionItem', $this->sConsumptionItem]);
        }
        if ($this->sRemark) {
            $query->andWhere(['like', 'sRemark', $this->sRemark]);
        }   
        if ($this->sDate) {
            $query->andWhere(['sDate'=> $this->sDate]);
        }                

        $data = $query->createCommand()->queryOne();

        return $data['summary'];
    }
}
