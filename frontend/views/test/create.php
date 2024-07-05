<?php

use common\models\Subject;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $model */

$this->title = Yii::t('app', 'Добавить тест');
?>
<div class="test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
    ?>

    <?= $form->field($model, 'subject_id')->dropDownList(
        $subjects,
        ['prompt' => 'Выберите предмет'])->label('Предмет') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Заголовок') ?>

    <div class="mt-2">
        <?= $form->field($model, 'has_equation')->checkbox(['class' => 'form-check-input'])->label('Есть формулы') ?>
    </div>


    <?= $form->field($model, 'test')->textarea(['rows' => 7])->label('Тест') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
