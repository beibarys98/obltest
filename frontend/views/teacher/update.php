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

    <?php
    $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
    ?>

    <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
        'data' => $schools,
        'options' => ['placeholder' => 'Мектеп таңдаңыз'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Мектеп');?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model2, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Пән таңдаңыз'])->label('Пән') ?>

    <?= $form->field($model, 'password')->passwordInput()->label('Құпия сөз өзгерту') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
