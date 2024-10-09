<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%percentage}}".
 *
 * @property int $id
 * @property int|null $first
 * @property int|null $second
 * @property int|null $third
 * @property int|null $good
 * @property int|null $participant
 */
class Percentage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%percentage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first', 'second', 'third', 'good', 'participant'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first' => Yii::t('app', 'First'),
            'second' => Yii::t('app', 'Second'),
            'third' => Yii::t('app', 'Third'),
            'good' => Yii::t('app', 'Good'),
            'participant' => Yii::t('app', 'Participant'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PercentageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PercentageQuery(get_called_class());
    }
}
