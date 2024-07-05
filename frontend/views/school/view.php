<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="school-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены что хотите удалить?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'region.region',
                'label' => 'Регион'
            ],
            [
                'attribute' => 'town',
                'label' => 'Район'
            ],
            [
                'attribute' => 'name',
                'label' => 'Название'
            ],
        ],
    ]) ?>

</div>
