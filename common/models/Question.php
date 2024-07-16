<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%question}}".
 *
 * @property int $id
 * @property int|null $test_id
 * @property int|null $number
 * @property string|null $question
 * @property string|null $answer1
 * @property string|null $answer2
 * @property string|null $answer3
 * @property string|null $answer4
 * @property string|null $correct_answer
 *
 * @property Test $test
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer1', 'answer2', 'answer3', 'answer4', 'answer5'], 'required'],

            [['test_id', 'number'], 'integer'],
            [['question'], 'string', 'max' => 1000],
            [['answer1', 'answer2', 'answer3', 'answer4', 'correct_answer'], 'string', 'max' => 255],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'test_id' => Yii::t('app', 'Test ID'),
            'number' => Yii::t('app', 'Number'),
            'question' => Yii::t('app', 'Question'),
            'answer1' => Yii::t('app', 'Answer1'),
            'answer2' => Yii::t('app', 'Answer2'),
            'answer3' => Yii::t('app', 'Answer3'),
            'answer4' => Yii::t('app', 'Answer4'),
            'correct_answer' => Yii::t('app', 'Correct Answer'),
        ];
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TestQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\QuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuestionQuery(get_called_class());
    }
}
