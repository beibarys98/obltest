<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Subject $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="subject-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true])->label('Атауы') ?>

    <?= $form->field($model, 'subject_ru')->textInput(['maxlength' => true])->label('Название') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
