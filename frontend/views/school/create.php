<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = Yii::t('app', 'Create School');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schools'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
