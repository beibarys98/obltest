<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TestTaker $model */

$this->title = Yii::t('app', 'Create Test Taker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Test Takers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-taker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
