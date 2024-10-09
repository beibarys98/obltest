<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%subject}}".
 *
 * @property int $id
 * @property string $subject
 * @property string|null $subject_ru
 *
 * @property Teacher[] $teachers
 * @property Test[] $tests
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subject}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'subject_ru'], 'required'],
            [['subject', 'subject_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'subject_ru' => Yii::t('app', 'Subject Ru'),
        ];
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TeacherQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::class, ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[Tests]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TestQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::class, ['subject_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SubjectQuery(get_called_class());
    }
}
