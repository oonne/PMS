<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\UserAccount;

class UserAccountSearch extends UserAccount
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['sUserName', 'sAccessToken'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = UserAccount::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUserID' => SORT_DESC]]
        ]);

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'sUserName', $this->sUserName])
              ->andFilterWhere(['like', 'sAccessToken', $this->sAccessToken]);

        return $dataProvider;
    }
}