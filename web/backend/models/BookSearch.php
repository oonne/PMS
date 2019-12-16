<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Book;

class BookSearch extends Book
{
    public $dateRange;
        
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sBookTitle', 'tSummary', 'sIsGood', 'dateRange'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $dates = explode('~', $this ->dateRange, 2);
        if (count($dates) == 2){
            $_dateFrom = $dates[0];
            $_dateTo = $dates[1];
            $query->andFilterWhere(['>=', 'sDate', $_dateFrom ])
                  ->andFilterWhere(['<=', 'sDate', $_dateTo ]);
        }
        
        // grid filtering conditions
        $query->andFilterWhere(['like', 'sBookTitle', $this->sBookTitle])
              ->andFilterWhere(['like', 'tSummary', $this->tSummary])
              ->andFilterWhere(['sIsGood' => $this->sIsGood]);

        return $dataProvider;
    }
}
