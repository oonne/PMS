<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\helpers\Query as QueryHelper;
use common\models\Income;
use common\models\Donation;

class DonationSearch extends Donation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dMoney', 'sDate', 'sDonationItem', 'sRemark'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Donation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sDate' => SORT_DESC]]
        ]);

        $data = [];

        $incomeQuery = Income::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([Income::tableName()]);
        $incomeTotal = $incomeQuery->createCommand()->queryOne();
        $data['incomeTotal'] = floor($incomeTotal['summary']/10);

        $donationQuery = Donation::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([Donation::tableName()]);
        $donationTotal = $donationQuery->createCommand()->queryOne();
        $data['donationTotal'] = $donationTotal['summary'];

        if (!($this->load($params) && $this->validate())) {
            $data['dataProvider'] = $dataProvider;
            $data['summary'] = $this->_summary();
            return $data;
        }

        QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);

        $query->andFilterWhere(['like', 'sDonationItem', $this->sDonationItem])
              ->andFilterWhere(['like', 'sRemark', $this->sRemark])
              ->andFilterWhere(['sDate'=> $this->sDate]);

        $data['dataProvider'] = $dataProvider;
        $data['summary'] = $this->_summary();
        return $data;
    }

    public function _summary()
    {
        $query = Donation::find()
                    ->select(['summary' => 'SUM(dMoney)'])
                    ->from([Donation::tableName()]);

        if ($this->dMoney) {
            QueryHelper::addDigitalFilter($query, 'dMoney', $this->dMoney);
        }

        if ($this->sDonationItem) {
            $query->andWhere(['like', 'sDonationItem', $this->sDonationItem]);
        }
        if ($this->sRemark) {
            $query->andWhere(['like', 'sRemark', $this->sRemark]);
        }
        if ($this->sDate) {
            $query->andWhere(['sDate' => $this->sDate]);
        }          

        $data = $query->createCommand()->queryOne();

        return $data['summary'];
    }
}
