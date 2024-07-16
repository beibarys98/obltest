<?php

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

    <?php foreach ($questions as $q): ?>
        <div style="font-size: 24px;">
            <?= $q->number . '. '; ?>
            <?= $q->question; ?>
            <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'question'])):?>
                <br>
                <img src="<?= $f->path ?>"
                     class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
            <?php endif;?>
            <div class="d-flex ms-5">
                <?= 'a. '?>
                <?php if($q->answer1):?>
                    <span style="<?= $q->correct_answer == 'a' ? 'color: green;' : ($answers[$q->id] == 'a' ? 'color: red;' : '') ?>">
                        <?= $q->answer1;?>
                        <?= $answers[$q->id] == 'a' ? '✔ ' : ''; ?>
                    </span>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer1'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex ms-5">
                <?= 'b. '?>
                <?php if($q->answer2):?>
                    <span style="<?= $q->correct_answer == 'b' ? 'color: green;' : ($answers[$q->id] == 'b' ? 'color: red;' : '') ?>">
                        <?= $q->answer2;?>
                        <?= $answers[$q->id] == 'b' ? '✔ ' : ''; ?>
                    </span>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer2'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex ms-5">
                <?= 'c. '?>
                <?php if($q->answer3):?>
                    <span style="<?= $q->correct_answer == 'c' ? 'color: green;' : ($answers[$q->id] == 'c' ? 'color: red;' : '') ?>">
                        <?= $q->answer3;?>
                        <?= $answers[$q->id] == 'c' ? '✔ ' : ''; ?>
                    </span>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer3'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex ms-5">
                <?= 'd. '?>
                <?php if($q->answer4):?>
                    <span style="<?= $q->correct_answer == 'd' ? 'color: green;' : ($answers[$q->id] == 'd' ? 'color: red;' : '') ?>">
                        <?= $q->answer4;?>
                        <?= $answers[$q->id] == 'd' ? '✔ ' : ''; ?>
                    </span>
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

        <?php
        if ($answers[$q->id] == $q->correct_answer) {
            $score++;
        }
        ?>

    <?php endforeach; ?>

    <!-- print score -->
    <div style="font-weight: bold;">
        <h3>Результат: <?= $score ?> / <?= $totalQuestions ?></h3>
    </div>
</div>
