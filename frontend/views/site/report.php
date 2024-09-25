<?php

use common\models\Answer;
use yii\helpers\Html;
use yii\helpers\Url;

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
                $isCorrect = $a->answer == Answer::findOne($q->correct_answer)->answer;
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
                    <?php if ($a->formula): ?>
                        <!-- Display the formula image if it exists for the answer -->
                        <?= $alphabet[$index++] . '. ' ?>
                        <?= Html::img(Url::to('@web/' . $a->formula)) ?>
                    <?php else: ?>
                        <!-- Display the answer text if no formula exists -->
                        <?= $alphabet[$index++] . '. ' . Html::encode($a->answer); ?>
                    <?php endif; ?>
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
