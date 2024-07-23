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

$startTime = time();
$endTime = strtotime($test->end_time);
$durationInSeconds = $endTime - $startTime;
$durationInSeconds = max($durationInSeconds, 0);

$this->registerJs("
    function startTimer(duration, display) {
        var timer = duration, hours, minutes, seconds;
        var interval = setInterval(function () {
            hours = parseInt(timer / 3600, 10); // Calculate hours
            minutes = parseInt((timer % 3600) / 60, 10); // Calculate minutes
            seconds = parseInt(timer % 60, 10); // Calculate seconds

            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            display.textContent = hours + ':' + minutes + ':' + seconds;

            if (--timer < 0) {
                timer = 0; // Stop at 0
                clearInterval(interval); // Stop the timer
                document.getElementById('myForm').submit(); // Submit the form
            }
        }, 1000);
    }

    window.onload = function () {
        var duration = $durationInSeconds; // Countdown duration in seconds
        var display = document.querySelector('#clock'); // Timer display element
        startTimer(duration, display);
    };
", View::POS_END);
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
        width: 150px; height: 90px; border: 1px solid black;
        border-radius: 10px; text-align: center;">
        <div class="site-index">
            <div style="color: red;">
                Парақшаны жаңартпаңыз!
            </div>
            <div class="jumbotron">
                <p id="clock" style="font-size: 24px;"></p>
            </div>
        </div>
    </div>

</div>
