<?php

use common\models\Formula;
use yii\helpers\Html;
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
                <span style="<?= $q->answer4 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= 'a. '?>
                </span>
                <?php if($q->answer1):?>
                    <span style="<?= $q->answer1 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= $q->answer1;?>
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
                <span style="<?= $q->answer4 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= 'b. '?>
                </span>
                <?php if($q->answer2):?>
                    <span style="<?= $q->answer2 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= $q->answer2;?>
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
                <span style="<?= $q->answer4 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= 'c. '?>
                </span>
                <?php if($q->answer3):?>
                    <span style="<?= $q->answer3 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= $q->answer3;?>
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

                <span style="<?= $q->answer4 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= 'd. '?>
                </span>
                <?php if($q->answer4):?>
                    <span style="<?= $q->answer4 == $q->correct_answer ? 'color: green;' : '' ?>">
                        <?= $q->answer4;?>
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
    <?php endforeach; ?>

    <?php foreach ($answers as $a):?>
    <?= $a?>
    <?php endforeach;?>

</div>
