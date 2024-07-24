<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ResultPdf $model */

$this->title = Yii::t('app', 'Create Result Pdf');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Result Pdfs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="result-pdf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
