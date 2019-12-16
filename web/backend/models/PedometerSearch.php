<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\Pedometer;

class PedometerSearch extends Pedometer
{
    public $dateRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uStep', 'dateRange'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Pedometer::find();

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

        QueryHelper::addDigitalFilter($query, 'uStep', $this->uStep);

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2){
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }

        $data['dataProvider'] = $dataProvider;
        $data['summary'] = $this->_summary();
        return $data;
    }

    public function _summary()
    {
        $query = Pedometer::find()
                    ->select(['summary' => 'SUM(uStep)'])
                    ->from([Pedometer::tableName()]);

        if ($this->uStep) {
            QueryHelper::addDigitalFilter($query, 'uStep', $this->uStep);
        }

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2) {
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }          

        $data = $query->createCommand()->queryOne();

        return $data['summary'];
    }
}
