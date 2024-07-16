<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%certificate}}".
 *
 * @property int $id
 * @property int|null $teacher_id
 * @property string|null $certificate
 *
 * @property Teacher $teacher
 */
class Certificate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%certificate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id'], 'integer'],
            [['certificate'], 'string', 'max' => 255],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
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
            'certificate' => Yii::t('app', 'Certificate'),
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
     * {@inheritdoc}
     * @return \common\models\query\CertificateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CertificateQuery(get_called_class());
    }
}
