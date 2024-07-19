<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property string|null $title
 * @property string|null $test
 * @property int|null $has_equation
 * @property string|null $status
 * @property string|null $start_time
 * @property string|null $end_time
 *
 * @property Question[] $questions
 * @property Subject $subject
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'has_equation'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
            [['test'], 'string', 'max' => 10000],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'title' => Yii::t('app', 'Title'),
            'test' => Yii::t('app', 'Test'),
            'has_equation' => Yii::t('app', 'Has Equation'),
            'status' => Yii::t('app', 'Status'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
        ];
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuestionQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SubjectQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TestQuery(get_called_class());
    }
}
