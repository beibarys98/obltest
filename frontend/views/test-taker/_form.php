<?php

use common\models\Test;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\TestTaker $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-taker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'teacher_id')->textInput() ?>

    <?php
    $testItems = ArrayHelper::map(Test::find()->all(), 'id', function($model) {
        return $model->id . ' - ' . $model->title;
    });    ?>

    <?= $form->field($model, 'test_id')->dropDownList(
        $testItems,
        ['prompt' => 'Select a test']
    ) ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
