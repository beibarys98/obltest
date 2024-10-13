<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PaymentSearch represents the model behind the search form of `common\models\Payment`.
 */
class PaymentSearch extends Payment
{
    public $username;
    public $name;
    public $subject;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'test_id'], 'integer'],
            [['payment', 'created_at', 'name', 'subject', 'username'], 'safe'],
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
        $query = Payment::find();

        // add conditions that should always apply here

        $query->joinWith(['teacher']);
        $query->joinWith(['test.subject']);
        $query->joinWith(['teacher.user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'username',
                    'name',
                    'subject',
                    'created_at'
                ]
            ]
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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'payment', $this->payment]);
        $query->andFilterWhere(['like', 'teacher.name', $this->name]);
        $query->andFilterWhere(['like', 'subject.subject', $this->subject]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);

        return $dataProvider;
    }
}
