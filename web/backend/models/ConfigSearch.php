<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Config;

class ConfigSearch extends Config
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sConfigName', 'sConfigKey', 'tConfigValue'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Config::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['uUpdatedTime' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'sConfigName', $this->sConfigName])
              ->andFilterWhere(['like', 'sConfigKey', $this->sConfigKey])
              ->andFilterWhere(['like', 'tConfigValue', $this->tConfigValue]);

        return $dataProvider;
    }
}
