<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%certificate}}".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property string|null $certificate
 *
 * @property Subject $subject
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
            [['subject_id'], 'integer'],
            [['certificate'], 'string', 'max' => 255],
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
            'certificate' => Yii::t('app', 'Certificate'),
        ];
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
     * @return \common\models\query\CertificateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CertificateQuery(get_called_class());
    }
}
