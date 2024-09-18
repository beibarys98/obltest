<?php

use common\models\Answer;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions */
/** @var $formula */

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="font-size: 24px;">
        <?php $number = 1; ?>
        <?php foreach ($questions as $q): ?>
            <?= $number++ . '. '; ?>
            <?= $q->question; ?>
            <br>
            <?php
            $answers = Answer::find()
                ->andWhere(['question_id' => $q->id])
                ->all();
            $alphabet = range('A', 'Z'); // Array of alphabet letters
            $index = 0; // Initialize index for letters
            ?>
            <?php foreach ($answers as $a): ?>
                <?php if ($a->answer === $q->correct_answer): ?>
                    <strong><?= $alphabet[$index++] . '. ' . $a->answer ?></strong><br>
                <?php else: ?>
                    <?= $alphabet[$index++] . '. ' . $a->answer ?><br>
                <?php endif; ?>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
    </div>

    <div class="">
        <?= Html::a(Yii::t('app', 'Сақтау'),
            ['test/view', 'id' => $test->id],
            [
                'class' => 'btn btn-success rounded-circle shadow',
                'style' => 'position: fixed;
                                bottom: 10%;
                                right: 9%;
                                z-index: 1000;
                                width: 107px;
                                height: 107px;
                                display: flex;
                                justify-content: center;
                                align-items: center;'
            ]) ?>
    </div>

</div>
