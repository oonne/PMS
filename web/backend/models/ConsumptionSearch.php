<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\Consumption;

class ConsumptionSearch extends Consumption
{
    public $dateRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dMoney', 'sConsumptionItem', 'Category', 'sRemark', 'dateRange'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Consumption::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC, 'uUpdatedTime' => SORT_DESC]]
        ]);

        $data = [];

        if (!($this->load($params) && $this->validate())) {
            $data['dataProvider'] = $dataProvider;
            $data['summary'] = $this->_summary();
            return $data;
        }

        QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2) {
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }

        $query->andFilterWhere(['like', 'sConsumptionItem', $this->sConsumptionItem])
              ->andFilterWhere(['Category' => $this->Category])
              ->andFilterWhere(['like', 'sRemark', $this->sRemark]);

        $data['dataProvider'] = $dataProvider;
        $data['summary'] = $this->_summary();
        return $data;
    }

    public function _summary()
    {
        $query = Consumption::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([Consumption::tableName()]);

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

        if ($this->sConsumptionItem) {
            $query->andWhere(['like', 'sConsumptionItem', $this->sConsumptionItem]);
        }
        if ($this->Category) {
            $query->andWhere(['Category' => $this->Category]);
        }
        if ($this->sRemark) {
            $query->andWhere(['like', 'sRemark', $this->sRemark]);
        }                

        $data = $query->createCommand()->queryOne();

        return $data['summary'];
    }

}
