<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property int $id
 * @property string|null $region
 *
 * @property School[] $schools
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region' => Yii::t('app', 'Region'),
        ];
    }

    /**
     * Gets query for [[Schools]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SchoolQuery
     */
    public function getSchools()
    {
        return $this->hasMany(School::class, ['region_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RegionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RegionQuery(get_called_class());
    }
}
