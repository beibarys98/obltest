<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions */
/** @var $formula */

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?php foreach ($questions as $q): ?>
        <div style="font-size: 24px;">
            <?= $q->number . '. '; ?>
            <?= $q->question; ?>
            <?= $form->field($formula, 'files[]')
                ->fileInput(['multiple' => true, 'class' => 'w-25'])
                ->label(false) ?>
            <?= $form->field($formula, 'question_id')->hiddenInput(['value' => $q->id])->label(false); ?>
            <?= $form->field($formula, 'type')->hiddenInput(['value' => 'question'])->label(false); ?>

            <div class="d-flex">
                <?= 'a. '?>
                <?php if($q->answer1):?>
                <?= $q->answer1;?>
                <?php else:?>
                    <?= $form->field($formula, 'files[]')
                        ->fileInput(['multiple' => true, 'class' => 'ms-1'])
                        ->label(false) ?>
                    <?= $form->field($formula, 'question_id')->hiddenInput(['value' => $q->id])->label(false); ?>
                    <?= $form->field($formula, 'type')->hiddenInput(['value' => 'answer1'])->label(false); ?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'b. '?>
                <?php if($q->answer2):?>
                    <?= $q->answer2;?>
                <?php else:?>
                    <?= $form->field($formula, 'files[]')
                        ->fileInput(['multiple' => true, 'class' => 'ms-1'])
                        ->label(false) ?>
                    <?= $form->field($formula, 'question_id')->hiddenInput(['value' => $q->id])->label(false); ?>
                    <?= $form->field($formula, 'type')->hiddenInput(['value' => 'answer2'])->label(false); ?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'c. '?>
                <?php if($q->answer3):?>
                    <?= $q->answer3;?>
                <?php else:?>
                    <?= $form->field($formula, 'files[]')
                        ->fileInput(['multiple' => true, 'class' => 'ms-1'])
                        ->label(false) ?>
                    <?= $form->field($formula, 'question_id')->hiddenInput(['value' => $q->id])->label(false); ?>
                    <?= $form->field($formula, 'type')->hiddenInput(['value' => 'answer3'])->label(false); ?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'd. '?>
                <?php if($q->answer4):?>
                    <?= $q->answer4;?>
                <?php else:?>
                    <?= $form->field($formula, 'files[]')
                        ->fileInput(['multiple' => true, 'class' => 'ms-1'])
                        ->label(false) ?>
                    <?= $form->field($formula, 'question_id[]')->hiddenInput(['value' => $q->id])->label(false); ?>
                    <?= $form->field($formula, 'type[]')->hiddenInput(['value' => 'answer4'])->label(false); ?>
                <?php endif;?>
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
