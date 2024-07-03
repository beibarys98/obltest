<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%school}}".
 *
 * @property int $id
 * @property int|null $region_id
 * @property string $school
 *
 * @property Region $region
 * @property Teacher[] $teachers
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%school}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school'], 'required'],
            [['school'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region_id' => Yii::t('app', 'Region ID'),
            'school' => Yii::t('app', 'School'),
        ];
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RegionQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TeacherQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::class, ['school_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SchoolQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SchoolQuery(get_called_class());
    }
}
