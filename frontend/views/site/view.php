<?php

use common\models\Answer;
use common\models\Question;
use common\models\Teacher;
use common\models\TeacherAnswer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\web\YiiAsset;

/** @var $this */
/** @var $test */
/** @var $question*/
/** @var $testTaker*/

$this->title = $test->title;
YiiAsset::register($this);

// Assuming $test->duration is in HH:MM:SS format
$durationArray = explode(':', $test->duration);
$totalDurationInSeconds = ($durationArray[0] * 3600) + ($durationArray[1] * 60) + $durationArray[2];
$totalDurationInSeconds = max($totalDurationInSeconds, 0);

// Create DateTime objects for start time and current time
$startTime = new DateTime($testTaker->start_time);
$currentTime = new DateTime('now');

// Calculate the elapsed time in seconds
$elapsedTimeInSeconds = $currentTime->getTimestamp() - $startTime->getTimestamp();

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
                timer = 0;
                clearInterval(interval);
                window.location = \"" . Url::to(['site/index']) . "\";
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

    <div class="d-flex">
        <div style="width: 70%;" class="p-3">
            <div class="p-3" style="border: 1px solid black; border-radius: 10px; font-size: 24px;
    user-select: none; -webkit-user-select: none; -moz-user-select: none;
    -ms-user-select: none;">
                <?php if ($question->formula): ?>
                    <!-- Display the formula image if it exists -->
                    <?= Html::img(Url::to('@web/' . $question->formula)) ?>
                <?php else: ?>
                    <!-- Display the question text if no formula exists -->
                    <?= Html::encode($question->question); ?>
                <?php endif; ?>
                <br>

                <?php
                $answers = Answer::find()
                    ->andWhere(['question_id' => $question->id])
                    ->all();
                $alphabet = range('A', 'Z'); // Array of alphabet letters
                $index = 0; // Initialize index for letters
                ?>

                <form id="answerForm" action="<?= Url::to(['site/submit']) ?>" method="get">
                    <?php
                    // Find the TeacherAnswer for this question, if it exists
                    $teacherAnswer = TeacherAnswer::find()
                        ->andWhere(['teacher_id' => Teacher::findOne(['user_id' => Yii::$app->user->id])->id,
                            'question_id' => $question->id])->one();
                    $selectedAnswerId = $teacherAnswer ? $teacherAnswer->answer_id : null;
                    ?>

                    <?php foreach ($answers as $a): ?>
                        <input type="radio" name="answer_id" value="<?= $a->id ?>"
                               class="form-check-input me-1"
                            <?= $selectedAnswerId == $a->id ? 'checked' : '' ?>>
                        <?php if ($a->formula): ?>
                            <!-- Display the formula image if it exists for the answer -->
                            <?= $alphabet[$index++] . '. ' ?>
                            <?= Html::img(Url::to('@web/' . $a->formula)) ?><br>
                        <?php else: ?>
                            <!-- Display the answer text if no formula exists -->
                            <?= $alphabet[$index++] . '. ' . Html::encode($a->answer); ?><br>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <input type="hidden" name="question_id" value="<?= $question->id ?>">

                    <!-- Submit button to trigger form submission via GET -->
                    <button type="submit" class="btn btn-primary mt-5 w-100">
                        <?= Yii::t('app', 'Сохранить') ?>
                    </button>
                </form>
            </div>
        </div>

        <?php
        $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
        ?>

        <div class="p-3" style="width: 30%;">
            <div class="p-3" style="border: 1px solid black; border-radius: 10px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: center;">
                    <?php $index = 1; // Initialize counter ?>
                    <?php foreach ($questions as $q): ?>
                        <?php
                        // Find the corresponding TeacherAnswer, if it exists
                        $teacherAnswer2 = TeacherAnswer::find()
                            ->andWhere(['teacher_id' => Teacher::findOne(['user_id' => Yii::$app->user->id])->id,
                                'question_id' => $q->id])->one();
                        $backgroundColor = $teacherAnswer2 && $teacherAnswer2->answer_id ? 'green' : 'red';
                        $borderStyle = ($q->id == $question->id) ? '5px solid black' : 'none';
                        ?>
                        <a href="<?= Url::to(['view', 'id' => $q->id]) ?>" style="text-decoration: none;">
                            <div style="width: 30px; height: 30px; display: flex; align-items: center;
                                    justify-content: center; border: <?= $borderStyle ?>;
                                    background-color: <?= $backgroundColor ?>;
                                    color: white; font-size: 14px; margin: 2px; border-radius: 5px;">
                                <?= $index++ // Increment and display the counter ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="jumbotron w-100" style="text-align: center;">
                    <div id="clock" class="mt-5 mb-5" style="border: 1px solid black;
                        border-radius: 10px; font-size: 24px;"></div>

                    <a href="<?= Url::to(['site/end', 'id' => $test->id]) ?>" class="btn btn-danger w-100">
                        <?= Yii::t('app', 'Завершить') ?>
                    </a>
                </div>
            </div>

        </div>
</div>
