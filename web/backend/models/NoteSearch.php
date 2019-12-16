<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Note;

class NoteSearch extends Note
{
    public $updatedTimeRange;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sNoteTitle', 'tNoteContent', 'updatedTimeRange'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Note::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUpdatedTime' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $dates = explode('~', $this ->updatedTimeRange, 2);
        if (count($dates) == 2){
            $query->andFilterWhere(['>=', 'uUpdatedTime', $dates[0] ])
                  ->andFilterWhere(['<=', "DATE_FORMAT(`uUpdatedTime`, '%Y-%m-%d')", $dates[1] ]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'sNoteTitle', $this->sNoteTitle])
              ->andFilterWhere(['like', 'tNoteContent', $this->tNoteContent]);

        return $dataProvider;
    }
}
