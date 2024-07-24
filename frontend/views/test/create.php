<?php

use common\models\Subject;
use kartik\datetime\DateTimePicker;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $model */

$this->title = Yii::t('app', 'Жаңа тест');
?>
<div class="test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Атауы') ?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Пән таңдаңыз'])->label('Пән') ?>

    <?= $form->field($model, 'test')->textarea(['rows' => 7])->label('Тест') ?>

    <?= $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Күнін және уақытын таңдаңыз'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true
        ]
    ])->label('Басталуы');?>
    <?= $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Күнін және уақытын таңдаңыз'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true
        ]
    ])->label('Аяқталуы');?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
