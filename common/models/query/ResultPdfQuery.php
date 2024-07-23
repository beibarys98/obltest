<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\ResultPdf]].
 *
 * @see \common\models\ResultPdf
 */
class ResultPdfQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\ResultPdf[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ResultPdf|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
