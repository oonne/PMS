<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\Weather;

class WeatherSearch extends Weather
{
    public $dateRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Station', 'dTemperature', 'dHumidity', 'dateRange'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Weather::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sTime' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        QueryHelper::addDigitalFilter($query, 'dTemperature', $this->dTemperature);
        QueryHelper::addDigitalFilter($query, 'dHumidity', $this->dHumidity);

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2){
            $_dateFrom = $dates[0] . '--00';
            $_dateTo = $dates[1] . '--24';
            $query->andFilterWhere(['>=', 'sTime', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sTime', $_dateTo ]);
        }

        $query->andFilterWhere(['Station' => $this->Station]);

        return $dataProvider;
    }
}
