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
 * @property string|null $language
 * @property int|null $version
 * @property string|null $status
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $duration
 *
 * @property File[] $files
 * @property Payment[] $payments
 * @property Question[] $questions
 * @property ResultPdf[] $resultPdfs
 * @property Result[] $results
 * @property StartTime[] $startTimes
 * @property Subject $subject
 */
class Test extends \yii\db\ActiveRecord
{
    public $file;
    public $date;

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
            [['date'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Күні yyyy-mm-dd форматында болуы тиіс.'],
            [['title', 'subject_id', 'test', 'language', 'version', 'date', 'start_time', 'end_time', 'duration'], 'required'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
            [['version'], 'match', 'pattern' => '/^\d+$/', 'message' => 'Нұсқа тек сан бола алады.'],
            [['subject_id', 'version'], 'integer'],
            [['start_time', 'end_time', 'duration'], 'safe'],
            [['title', 'test', 'language', 'status'], 'string', 'max' => 255],
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
            'language' => Yii::t('app', 'Language'),
            'version' => Yii::t('app', 'Version'),
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
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PaymentQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['test_id' => 'id']);
    }

    public function getPayment()
    {
        return $this->hasMany(Payment::class, ['test_id' => 'id']);
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
     * Gets query for [[StartTimes]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\StartTimeQuery
     */
    public function getStartTimes()
    {
        return $this->hasMany(StartTime::class, ['test_id' => 'id']);
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
