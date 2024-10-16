<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TestTaker;

/**
 * TestTakerSearch represents the model behind the search form of `common\models\TestTaker`.
 */
class TestTakerSearch extends TestTaker
{
    public $username;
    public $name;
    public $created_at;
    public $result;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'test_id', 'result'], 'integer'],
            [['start_time', 'end_time', 'username', 'name','created_at'], 'safe'],
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
        $query = TestTaker::find();

        // add conditions that should always apply here

        $query->andWhere(['test_id' => $this->test_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
        ]);

        return $dataProvider;
    }
}
