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
 * @property string|null $status
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $duration
 *
 * @property File[] $files
 * @property Question[] $questions
 * @property ResultPdf[] $resultPdfs
 * @property Result[] $results
 * @property Subject $subject
 */
class Test extends \yii\db\ActiveRecord
{
    public $file;

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
            [['subject_id', 'start_time', 'end_time', 'duration', 'title'], 'required'],

            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'doc, docx'],
            [['subject_id'], 'integer'],
            [['start_time', 'end_time', 'duration'], 'safe'],
            [['title', 'test', 'status'], 'string', 'max' => 255],
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
            'status' => Yii::t('app', 'Status'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'duration' => Yii::t('app', 'Duration'),
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['test_id' => 'id']);
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
     * Gets query for [[ResultPdfs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ResultPdfQuery
     */
    public function getResultPdfs()
    {
        return $this->hasMany(ResultPdf::class, ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ResultQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['test_id' => 'id']);
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
