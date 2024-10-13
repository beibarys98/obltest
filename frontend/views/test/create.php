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

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Атауы') ?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'subject_id')->dropDownList(
                $subjects,
                ['prompt' => ''])->label('Пән') ?>

        </div>
        <div class="col-lg-4">
            <?php
            $languages = [
                'kz' => 'қазақ тобы',
                'ru' => 'орыс тобы',
            ];
            ?>

            <?= $form->field($model, 'language')->dropDownList(
                $languages,
                ['prompt' => ''])
                ->label('Тест тапсыру тілі')?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'version')->textInput()->label('Нұсқа') ?>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'file')
                ->input('file', ['class' => 'form-control'])
                ->label('Тест') ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'minView' => 2,
                    'startView' => 2,
                    'showMeridian' => false,
                    'weekStart' => 1
                ]
            ])->label('Өтілім күні'); ?>
        </div>


        <div class="col-lg-4">
            <div style="width: 100%;">
                <?= $form->field($model, 'duration')->input('time')->label('Узақтығы');?>
            </div>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success w-100']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
