<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Password;

class PasswordSearch extends Password
{
        
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sPasswordItem', 'sUserName', 'tRemark'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Password::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUpdatedTime' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'sPasswordItem', $this->sPasswordItem])
              ->andFilterWhere(['like', 'sUserName', $this->sUserName])
              ->andFilterWhere(['like', 'tRemark', $this->tRemark]);

        return $dataProvider;
    }
}
