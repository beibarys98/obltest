<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%formula}}".
 *
 * @property int $id
 * @property int|null $question_id
 * @property string|null $type
 * @property string|null $path
 *
 * @property Question $question
 */
class Formula extends \yii\db\ActiveRecord
{
    public $files;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%formula}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 100],

            [['question_id'], 'integer'],
            [['type', 'path'], 'string', 'max' => 255],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuestionQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\FormulaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FormulaQuery(get_called_class());
    }
}
