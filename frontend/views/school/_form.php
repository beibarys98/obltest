<?php

use common\models\Region;
use common\models\Subject;
use common\models\Town;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\School $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="school-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $regions = ArrayHelper::map(Region::find()->all(), 'id', 'region');
    ?>

    <?= $form->field($model, 'region_id')->dropDownList(
        $regions,
        ['prompt' => 'Select Region']) ?>

    <?php
    $town = ArrayHelper::map(Town::find()->all(), 'id', 'name');
    ?>

    <?= $form->field($model, 'town')->widget(Select2::classname(), [
        'data' => $town,
        'options' => ['placeholder' => 'Select Town'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'name')->textInput()?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
