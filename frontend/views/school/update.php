<?php

use common\models\Region;
use common\models\Town;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\School $model */

$this->title = Yii::t('app', 'Изменить: {name}', [
    'name' => $model->name,
]);
?>
<div class="school-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $regions = ArrayHelper::map(Region::find()->all(), 'id', 'region');
    ?>

    <?= $form->field($model, 'region_id')->dropDownList(
        $regions,
        ['prompt' => 'Выберите регион'])->label('Регион') ?>

    <?php
    $town = ArrayHelper::map(Town::find()->all(), 'name', 'name');
    ?>

    <?= $form->field($model, 'town')->widget(Select2::classname(), [
        'data' => $town,
        'options' => ['placeholder' => 'Выберите район'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Район');?>

    <?= $form->field($model, 'name')->textInput()?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
