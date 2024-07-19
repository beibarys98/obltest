<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $test_id
 * @property string $path
 *
 * @property Teacher $teacher
 * @property Test $test
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'test_id', 'path'], 'required'],
            [['teacher_id', 'test_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
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
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery|TeacherQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery|TestQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }
}
