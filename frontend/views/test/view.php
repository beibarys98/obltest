<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $model */
/** @var $model2*/

$this->title = $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Опубликовать'),
            ['publish', 'id' => $model->id],
            ['class' => 'btn btn-success']) ?>
        <?= $model->has_equation
            ? Html::a(Yii::t('app', 'Формулы'),
                ['formula', 'id' => $model->id],
                ['class' => 'btn btn-primary'])
            : null ?>
        <?= Html::a(Yii::t('app', 'Изменить'),
            ['update', 'id' => $model->id],
            ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'),
            ['delete', 'id' => $model->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Вы уверены что хотите удалить?'),
                    'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php foreach ($model2 as $m2): ?>
        <div style="font-size: 24px;">
            <?= $m2->number . '. '; ?>
            <?= $m2->question; ?>
            <div class="ms-5">
                <?= 'a. ' . $m2->answer1; ?>
            </div>
            <div class="ms-5">
                <?= 'b. ' . $m2->answer2; ?>
            </div>
            <div class="ms-5">
                <?= 'c. ' . $m2->answer3; ?>
            </div>
            <div class="ms-5">
                <?= 'd. ' . $m2->answer4; ?>
            </div>
        </div>
        <br>
    <?php endforeach; ?>


</div>
