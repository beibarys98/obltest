<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%start_time}}".
 *
 * @property int $id
 * @property int|null $teacher_id
 * @property int|null $test_id
 * @property string $start_time
 *
 * @property Teacher $teacher
 * @property Test $test
 */
class StartTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%start_time}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'test_id'], 'integer'],
            [['start_time'], 'safe'],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
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
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'test_id' => Yii::t('app', 'Test ID'),
            'start_time' => Yii::t('app', 'Start Time'),
        ];
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TeacherQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
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
     * @return \common\models\query\StartTimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\StartTimeQuery(get_called_class());
    }
}
