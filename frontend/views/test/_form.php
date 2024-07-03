<?php

use common\models\Subject;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Test $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Select Subject']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="mt-2">
        <?= $form->field($model, 'has_equation')->checkbox(['class' => 'form-check-input']) ?>
    </div>


    <?= $form->field($model, 'test')->textarea(['rows' => 7]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
