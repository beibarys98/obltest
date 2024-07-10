<?php

use common\models\Question;
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

    <?= $form->field($model, 'title')->textInput(['style' => 'font-size: 24px;'])->label(false)?>

    <div class="mt-2">
        <?= $form->field($model, 'has_equation')->checkbox(['class' => 'form-check-input'])->label('Есть формулы') ?>
    </div>

    <?php foreach ($model2 as $m2): ?>
        <div style="font-size: 24px;">
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
                    <?= 'Правильный ответ'; ?>
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
        <?= Html::submitButton(Yii::t('app', 'Сохранить'),
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
