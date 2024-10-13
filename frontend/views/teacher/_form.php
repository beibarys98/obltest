<?php

use common\models\Subject;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Teacher $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $model2 */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model2, 'name')->textInput(['autofocus' => true])->label(Yii::t('app', 'Ф.И.О.')) ?>

    <?= $form->field($model, 'username')->textInput()->label('Логин') ?>

    <?= $form->field($model2, 'school')->textInput()->label(Yii::t('app', 'Наименование организаций'));?>

    <?php
    // Determine which field to use based on the current language
    $subjectField = (Yii::$app->language === 'ru-RU') ? 'subject_ru' : 'subject';

    // Map the appropriate field for the subjects
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', $subjectField);
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

    <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Пароль')) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
