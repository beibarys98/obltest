<?php

use common\models\Answer;
use common\models\Formula;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/
/** @var $answers*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $score = 0;
    $totalQuestions = count($questions);
    ?>

    <div style="font-size: 24px;">
        <?php $number = 1; ?>
        <?php foreach ($questions as $q): ?>
            <?= $number++ . '. ' . htmlspecialchars($q->question) ?>
            <br>
            <?php
            $answersModel = Answer::find()
                ->andWhere(['question_id' => $q->id])
                ->all();
            $alphabet = range('A', 'Z');
            $index = 0;
            ?>
            <?php foreach ($answersModel as $a): ?>
                <?php
                $isCorrect = $a->answer == $q->correct_answer;
                $isUserAnswer = isset($answers[$q->id]) && $answers[$q->id] == $a->answer;
                $color = '';

                if ($isCorrect) {
                    $color = 'color: green;';
                    // Increment score if the user's answer is correct
                    if ($isUserAnswer) {
                        $score++;
                    }
                } elseif ($isUserAnswer) {
                    $color = 'color: red;';
                }
                ?>
                <span style="<?= $color ?>">
                <?= $alphabet[$index++] . '. ' . htmlspecialchars($a->answer) ?>
            </span>
                <br>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
    </div>

    <!-- print score -->
    <div style="font-weight: bold;">
        <h3>Нәтиже: <?= $score ?> / <?= $totalQuestions ?></h3>
    </div>
</div>
