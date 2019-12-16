<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Recycle;

class RecycleSearch extends Recycle
{
    public $dateRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Category', 'tRecycleContent', 'dateRange'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Recycle::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sCreatedTime' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $recycleTime = explode('~', $this ->dateRange, 2);
        if (count($recycleTime) == 2){
            $query->andFilterWhere(['>=', 'sCreatedTime', $recycleTime[0] ])
                  ->andFilterWhere(['<=', "DATE_FORMAT(`sCreatedTime`, '%Y-%m-%d')", $recycleTime[1] ]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['Category' => $this->Category])
              ->andFilterWhere(['like', 'tRecycleContent', $this->tRecycleContent]);

        return $dataProvider;
    }
}
