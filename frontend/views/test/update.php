<?php

use common\models\Question;
use common\models\Subject;
use kartik\datetime\DateTimePicker;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $model */
/** @var $model2 */

$this->title = Yii::t('app', '{name}', [
    'name' => $model->title,
]);
?>
<div class="test-update">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="p-3 shadow" style="border: 1px solid black; border-radius: 10px;">
        <?php
        $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
        ?>

        <?= $form->field($model, 'subject_id')->dropDownList(
            $subjects,
            ['prompt' => 'Пән таңдаңыз'])->label('Пән') ?>

        <?= $form->field($model, 'title')->textInput()->label('Атауы')?>

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
    </div>
    <br>

    <?php foreach ($model2 as $m2): ?>
        <div>
            <div class="d-flex">
                <div class="me-3">
                    <?= $m2->number; ?>
                </div>
                <label class="w-50">
                    <input type="text" name="data[<?= $m2->id?>][question]" value="<?= $m2->question?>" class="form-control">
                </label>
            </div>

            <div class="d-flex mt-1">
                <div class="me-3 ms-5">
                    <?= 'a'; ?>
                </div>
                <label class="w-25">
                    <input type="text" name="data[<?= $m2->id?>][answer1]" value="<?= $m2->answer1?>" class="form-control">
                </label>
            </div>

            <div class="d-flex mt-1">
                <div class="me-3 ms-5">
                    <?= 'b'; ?>
                </div>
                <label class="w-25">
                    <input type="text" name="data[<?= $m2->id?>][answer2]" value="<?= $m2->answer2?>" class="form-control">
                </label>
            </div>

            <div class="d-flex mt-1">
                <div class="me-3 ms-5">
                    <?= 'c'; ?>
                </div>
                <label class="w-25">
                    <input type="text" name="data[<?= $m2->id?>][answer3]" value="<?= $m2->answer3?>" class="form-control">
                </label>
            </div>

            <div class="d-flex mt-1">
                <div class="me-3 ms-5">
                    <?= 'd'; ?>
                </div>
                <label class="w-25">
                    <input type="text" name="data[<?= $m2->id?>][answer4]" value="<?= $m2->answer4?>" class="form-control">
                </label>
            </div>

            <div class="d-flex mt-1">
                <div class="me-3 ms-5">
                    <?= 'Дұрыс жауабы'; ?>
                </div>
                <?php
                $answers = ['a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd'];
                ?>

                <label>
                    <?= Html::dropDownList("data[{$m2->id}][correct_answer]", $m2->correct_answer, $answers, ['class' => 'form-select']) ?>
                </label>
            </div>
        </div>
        <br>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'),
            [
                'class' => 'btn btn-success rounded-circle shadow',
                'style' => 'position: fixed;
                                bottom: 10%;
                                right: 9%;
                                z-index: 1000;
                                width: 107px;
                                height: 107px;'
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
