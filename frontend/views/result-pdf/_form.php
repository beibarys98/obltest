<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ResultPdf $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="result-pdf-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'test_id')->textInput() ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
