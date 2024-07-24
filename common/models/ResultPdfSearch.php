<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ResultPdf;

/**
 * ResultPdfSearch represents the model behind the search form of `common\models\ResultPdf`.
 */
class ResultPdfSearch extends ResultPdf
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'test_id'], 'integer'],
            [['path'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ResultPdf::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'test_id' => $this->test_id,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
