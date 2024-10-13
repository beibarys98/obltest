<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var $percentage*/
/** @var $purpose */

$this->title = 'Баптаулар'
?>
<div class="test-index">

    <h1 class="text-center">Баптаулар</h1>

    <div class="row w-100">
        <div class="col-4 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'receipts']) ?>" class="btn btn-primary w-100">
                Квитанцияларды жүктеп алу
            </a>
        </div>
        <div class="col-4 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'certificates']) ?>" class="btn btn-warning w-100">
                Сертификаттарды жүктеп алу
            </a>
        </div>
        <div class="col-4 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'reports']) ?>" class="btn btn-danger w-100">
                Қатемен жұмыстарды жүктеп алу
            </a>
        </div>
    </div>
    <div class="row w-100">
        <a href="<?= Url::to(['test/download-zip', 'type' => 'results']) ?>" class="btn btn-success w-100">
            Нәтижелерді жүктеп алу
        </a>
    </div>

    <br>

    <div class="p-3 shadow-sm" style="border: 1px solid black; border-radius: 10px; margin: 0 auto; width: 500px;">
        <?php $form = ActiveForm::begin(); ?>

        <div class="d-flex">
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'first')->textInput()->label('Бірінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'second')->textInput()->label('Екінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'third')->textInput()->label('Үшінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'good')->textInput()->label('Алғыс хат') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'participant')->textInput()->label('Сертификат') ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Сақтау', ['class' => 'btn btn-primary w-100',
                'style' => 'text-align: center;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <br>

    <div class="p-3 shadow-sm" style="border: 1px solid black; border-radius: 10px; margin: 0 auto; width: 400px;">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($purpose, 'purpose')->textInput()->label('Назначение платежа') ?>

        <?= $form->field($purpose, 'cost')->textInput()->label('Сумма') ?>

        <div class="form-group">
            <?= \yii\helpers\Html::submitButton('Сақтау',
                ['class' => 'btn btn-primary w-100', 'style' => 'text-align: center;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
