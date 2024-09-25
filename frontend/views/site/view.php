<?php

use common\models\Answer;
use yii\bootstrap5\ActiveForm;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\web\YiiAsset;

/** @var $this */
/** @var $test */
/** @var $questions*/
/** @var $startTime*/

$this->title = $test->title;
YiiAsset::register($this);

// Assuming $test->duration is in HH:MM:SS format
$durationArray = explode(':', $test->duration);
$totalDurationInSeconds = ($durationArray[0] * 3600) + ($durationArray[1] * 60) + $durationArray[2];
$totalDurationInSeconds = max($totalDurationInSeconds, 0);

// Create DateTime objects for start time and current time
$startTime2 = new DateTime($startTime->start_time); // Assuming $startTimeModel->start_time is in 'Y-m-d H:i:s' format
$currentTime = new DateTime('now');

// Calculate the elapsed time in seconds
$elapsedTimeInSeconds = $currentTime->getTimestamp() - $startTime2->getTimestamp();

// Calculate remaining time in seconds
$remainingTimeInSeconds = $totalDurationInSeconds - $elapsedTimeInSeconds;

// Ensure remaining time is not negative
$remainingTimeInSeconds = max($remainingTimeInSeconds, 0);

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
        var duration = $remainingTimeInSeconds; // Countdown duration in seconds
        var display = document.querySelector('#clock'); // Timer display element
        startTimer(duration, display);
    };
", View::POS_END);
?>

<div class="test-view">

    <?php $form = ActiveForm::begin([
        'id' => 'myForm',
        'action' => Url::to(['site/submit']),
        'method' => 'post',
    ]); ?>

    <?= Html::hiddenInput('test_id', $test->id) ?>

    <div style="font-size: 24px; user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php $number = 1; ?>
        <?php foreach ($questions as $q): ?>
            <?= $number++ . '. '; ?>
            <?php if ($q->formula): ?>
                <!-- Display the formula image if it exists -->
                <?= Html::img(Url::to('@web/' . $q->formula)) ?>
            <?php else: ?>
                <!-- Display the question text if no formula exists -->
                <?= Html::encode($q->question); ?>
            <?php endif; ?>
            <br>
            <?php
            $answers = Answer::find()
                ->andWhere(['question_id' => $q->id])
                ->orderBy(new Expression('RAND()'))
                ->all();
            $alphabet = range('A', 'Z'); // Array of alphabet letters
            $index = 0; // Initialize index for letters
            ?>
            <?php foreach ($answers as $a): ?>
                <input type="radio" name="answers[<?= $q->id ?>]" value="<?= $a->answer ?>" class="form-check-input me-1">
                <?php if ($a->formula): ?>
                    <!-- Display the formula image if it exists for the answer -->
                    <?= $alphabet[$index++] . '. ' ?>
                    <?= Html::img(Url::to('@web/' . $a->formula)) ?>
                    <br>
                <?php else: ?>
                    <!-- Display the answer text if no formula exists -->
                    <?= $alphabet[$index++] . '. ' . Html::encode($a->answer); ?><br>
                <?php endif; ?>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <?= Html::submitButton(Yii::t('app', 'Завершить'), [
            'class' => 'btn btn-success',
            'onclick' => 'return confirm("' . Yii::t('app', 'Вы уверены?') . '")',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="shadow" style="position: fixed;
        bottom: 10%; right: 9%; z-index: 1000;
        width: 150px; height: 90px; border: 1px solid black;
        border-radius: 10px; text-align: center;
        background-color: white;">
        <div class="site-index">
            <div style="color: red;">
                <?= Yii::t('app', 'Не обновляйте страницу!')?>
            </div>
            <div class="jumbotron">
                <p id="clock" style="font-size: 24px;"></p>
            </div>
        </div>
    </div>
</div>
