<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Diary;

class DiarySearch extends Diary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sDate', 'tDiaryContent'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Diary::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'tDiaryContent', $this->tDiaryContent])
              ->andFilterWhere(['sDate'=> $this->sDate]);

        return $dataProvider;
    }
}
