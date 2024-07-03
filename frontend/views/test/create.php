<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $model */

$this->title = Yii::t('app', 'Create Test');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
