<?php

namespace common\models;

use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $school
 * @property int|null $subject_id
 * @property string|null $language
 *
 * @property File[] $files
 * @property Payment[] $payments
 * @property Result[] $results
 * @property Subject $subject
 * @property TeacherAnswer[] $teacherAnswers
 * @property TestTaker[] $testTakers
 * @property User $user
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'subject_id'], 'integer'],
            [['name', 'school', 'language'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'school' => Yii::t('app', 'School'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'language' => Yii::t('app', 'Language'),
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PaymentQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ResultQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['teacher_id' => 'id']);
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
     * Gets query for [[TeacherAnswers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TeacherAnswerQuery
     */
    public function getTeacherAnswers()
    {
        return $this->hasMany(TeacherAnswer::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[TestTakers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TestTakerQuery
     */
    public function getTestTakers()
    {
        return $this->hasMany(TestTaker::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPayment(){
        return $this->hasOne(Payment::class, ['teacher_id' => 'id']);
    }

    public function getResult(){
        return $this->hasOne(Result::class, ['teacher_id' => 'id']);
    }

    public function getFile()
    {
        return $this->hasOne(File::class, ['teacher_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TeacherQuery(get_called_class());
    }
}
