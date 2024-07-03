<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $model */
/** @var $model2*/

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Опубликовать'), ['post', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php foreach ($model2 as $m2): ?>
        <div style="font-size: 24px;">
            <?= $m2->number . '. '; ?>
            <?= $m2->question; ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $m2->id ?>" id="answer<?= $m2->id ?>a">
                <label class="form-check-label" for="answer<?= $m2->id ?>a">
                    <?= 'a. ' . $m2->answer1; ?>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $m2->id ?>" id="answer<?= $m2->id ?>b">
                <label class="form-check-label" for="answer<?= $m2->id ?>b">
                    <?= 'b. ' . $m2->answer2; ?>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $m2->id ?>" id="answer<?= $m2->id ?>c">
                <label class="form-check-label" for="answer<?= $m2->id ?>c">
                    <?= 'c. ' . $m2->answer3; ?>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $m2->id ?>" id="answer<?= $m2->id ?>d">
                <label class="form-check-label" for="answer<?= $m2->id ?>d">
                    <?= 'd. ' . $m2->answer4; ?>
                </label>
            </div>
        </div>
        <br>
    <?php endforeach; ?>


</div>
