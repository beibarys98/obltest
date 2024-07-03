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

$this->title = Yii::t('app', 'Update Teacher: {name}', [
    'name' => $model2->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model2->name, 'url' => ['view', 'id' => $model2->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model2, 'name')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?php
    $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
    ?>

    <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
        'data' => $schools,
        'options' => ['placeholder' => 'Select School'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model2, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Select Subject']) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
