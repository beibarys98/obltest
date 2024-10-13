<?php

use common\models\Answer;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/
/** @var $answers*/
/** @var $testDP */
/** @var $resultDP */

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <div class="row">
        <div class="col-9">
            <?= GridView::widget([
                'dataProvider' => $testDP,
                'layout' => "{items}",
                'columns' => [
                    [
                        'attribute' => 'title',
                        'label' => 'Атауы'
                    ],
                    [
                        'attribute' => 'language',
                        'label' => 'Тіл'
                    ],
                    [
                        'attribute' => 'version',
                        'label' => 'Нұсқа'
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-3">
            <?= GridView::widget([
                'dataProvider' => $resultDP,
                'layout' => "{items}",
                'columns' => [
                    [
                        'attribute' => 'teacher.name',
                        'label' => 'Есімі'
                    ],
                    [
                        'attribute' => 'result',
                        'label' => 'Нәтиже'
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div style="font-size: 24px;">
        <?php $number = 1; ?>
        <?php foreach ($questions as $q): ?>
            <?= $number++ . '. ' . $q->question ?>
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
                $isCorrect = $a->id == $q->correct_answer;
                $isUserAnswer = isset($answers[$q->id])
                    && $answers[$q->id]->answer_id == $a->id;
                $color = '';

                if ($isCorrect) {
                    $color = 'color: green;';
                } elseif ($isUserAnswer) {
                    $color = 'color: red;';
                }
                ?>
                <span style="<?= $color ?>">
                    <?php if ($a->formula): ?>
                        <?= $alphabet[$index++] . '. ' ?>
                        <?= Html::img($a->formula) ?>
                    <?php else: ?>
                        <?= $alphabet[$index++] . '. ' . $a->answer; ?>
                    <?php endif; ?>
                </span>
                <br>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
    </div>
</div>
