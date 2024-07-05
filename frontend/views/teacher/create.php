<?php

use common\models\School;
use common\models\Subject;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Teacher $model */
/** @var $model2*/

$this->title = Yii::t('app', 'Create Teacher');
?>
<div class="teacher-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model2, 'name')->textInput(['autofocus' => true])->label('Имя') ?>

    <?= $form->field($model, 'username')->textInput()->label('Логин') ?>

    <?php
    $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
    ?>

    <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
        'data' => $schools,
        'options' => ['placeholder' => 'Выберите школу'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Школа');?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model2, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Выберите предмет'])->label('Предмет') ?>

    <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
