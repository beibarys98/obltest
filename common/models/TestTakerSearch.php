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
    public $subject;
    public $language;
    public $created_at;
    public $result;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'test_id', 'result'], 'integer'],
            [['start_time', 'end_time', 'username', 'name', 'subject', 'language','created_at'], 'safe'],
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

        $query->joinWith(['teacher.user']);
        $query->joinWith(['teacher']);
        $query->joinWith(['test.subject']);
        $query->joinWith(['test']);
        $query->joinWith(['teacher.payment']);
        $query->joinWith(['teacher.result']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'username',
                    'name',
                    'subject',
                    'language',
                    'created_at',
                    'start_time',
                    'end_time',
                    'result',
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->test_id) {
            $query->andWhere(['test_id' => $this->test_id]);
        }



        // grid filtering conditions
        $query->andFilterWhere([
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'teacher.name', $this->name]);
        $query->andFilterWhere(['like', 'subject.subject', $this->subject]);
        $query->andFilterWhere(['like', 'test.language', $this->language]);
        $query->andFilterWhere(['like', 'payment.created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'result.result', $this->result]);

        return $dataProvider;
    }
}
