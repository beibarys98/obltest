<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $test_id
 * @property string|null $result
 *
 * @property Teacher $teacher
 * @property Test $test
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'test_id'], 'required'],
            [['teacher_id', 'test_id'], 'integer'],
            [['result'], 'string', 'max' => 255],
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
            'result' => Yii::t('app', 'Result'),
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

    public function getTestTaker(){
        return $this->hasOne(TestTaker::class, ['test_taker.teacher_id' => 'teacher_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ResultQuery(get_called_class());
    }
}
