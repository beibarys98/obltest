<?php

use common\models\School;
use common\models\Subject;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var $model */
/** @var $model2*/

$this->title = Yii::t('app', '{name}', [
    'name' => $model2->name,
]);
?>
<div class="teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model2, 'name')->textInput(['autofocus' => true])->label('Есімі') ?>

    <?= $form->field($model, 'username')->textInput()->label('Логин') ?>

    <?= $form->field($model2, 'school')->textInput()->label('Мекеме атауы');?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model2, 'subject_id', ['labelOptions' => ['label' => Yii::t('app', 'Предмет')]])
        ->widget(Select2::classname(),
            [
                'data' => $subjects,
                'options' => [
                    'placeholder' => '',
                    'style' => ['width' => '100%'], // Ensure full width of the select box
                ],
                'pluginOptions' => [
                    'allowClear' => true, // Allow clearing the selection
                    'dropdownAutoWidth' => true, // Adjust dropdown width automatically
                    'maximumInputLength' => 20, // Optionally limit search length
                ],
            ]); ?>

    <?php
    $languages = [
        'kz' => Yii::t('app', 'казахский'),
        'ru' => Yii::t('app', 'русский'),
    ];
    ?>

    <?= $form->field($model2, 'language')->dropDownList(
        $languages,
        ['prompt' => ''])
        ->label(Yii::t('app', 'Язык сдачи теста'))?>

    <?= $form->field($model, 'password')->passwordInput()->label('Құпия сөз өзгерту') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>