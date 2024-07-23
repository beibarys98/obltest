<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Result]].
 *
 * @see \common\models\Result
 */
class ResultQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Result[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Result|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
