<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%town}}".
 *
 * @property int $id
 * @property string|null $name
 */
class Town extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%town}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TownQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TownQuery(get_called_class());
    }
}
