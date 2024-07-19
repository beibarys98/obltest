<?php

use common\models\Formula;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/
/** @var $answers*/

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
        <?= Html::submitButton('Завершить', [
            'class' => 'btn btn-success',
            'onclick' => 'return confirm("Вы уверены, что хотите завершить?")',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
