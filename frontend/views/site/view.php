<?php

use common\models\Formula;
use common\models\Test;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/
/** @var $answers*/
/** @var $remaining*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);

$timezone = new \DateTimeZone('Asia/Karachi'); // Adjust this if needed for GMT+5

$now = new \DateTime('now', $timezone);
$startTime = new \DateTime($test->start_time, $timezone);
$endTime = new \DateTime($test->end_time, $timezone);

// Check if the current time is within the start and end times
$isActive = $now >= $startTime && $now<= $endTime;
?>

<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'myForm',
        'action' => Url::to(['site/submit']),
        'method' => 'post',
    ]); ?>

    <?= Html::hiddenInput('test_id', $test->id) ?>

    <?php foreach ($questions as $q): ?>
        <div style="font-size: 24px;">
            <?= $q->number . '. '; ?>
            <?= $q->question; ?>
            <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'question'])):?>
                <br>
                <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                     class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
            <?php endif;?>

            <div class="d-flex ms-5">
                <input type="radio" name="answers[<?= $q->id ?>]" value="a" class="form-check-input me-1">
                <?= 'a. '?>

                <?php if($q->answer1):?>
                    <?= $q->answer1;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer1'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>

            <div class="d-flex ms-5">
                <input type="radio" name="answers[<?= $q->id ?>]" value="b" class="form-check-input me-1">
                <?= 'b. '?>

                <?php if($q->answer2):?>
                    <?= $q->answer2;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer2'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex ms-5">
                <input type="radio" name="answers[<?= $q->id ?>]" value="c" class="form-check-input me-1">
                <?= 'c. '?>

                <?php if($q->answer3):?>
                    <?= $q->answer3;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer3'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex ms-5">
                <input type="radio" name="answers[<?= $q->id ?>]" value="d" class="form-check-input me-1">
                <?= 'd. '?>

                <?php if($q->answer4):?>
                    <?= $q->answer4;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer4'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
        </div>
        <br>
    <?php endforeach; ?>

    <div class="text-center mt-4">
        <?= Html::submitButton('Аяқтау', [
            'class' => 'btn btn-success',
            'onclick' => 'return confirm("Сенімдісіз бе?")',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="shadow" style="position: fixed;
        bottom: 10%; right: 9%; z-index: 1000;
        width: 200px; height: 100px; border: 1px solid black;
        border-radius: 10px; text-align: center;">
        <div>
            Аяқталуы: <?= date('H:i', strtotime($test->end_time))?>
        </div>
        <div class="mt-1">
            Аяқталуына қалды
        </div>
        <?php
        $startTime = new DateTime();
        $endTime = new DateTime($test->end_time);
        $interval = $startTime->diff($endTime);
        $hours = str_pad($interval->h, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($interval->i, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($interval->s, 2, '0', STR_PAD_LEFT);
        $formattedTime = "$hours:$minutes:$seconds";
        ?>
        <?php \yii\widgets\Pjax::begin()?>
        <?= $this->render('_time_display', [
            'test' => $test,
            'time' => $formattedTime
        ])?>
        <?php \yii\widgets\Pjax::end()?>
    </div>

</div>
