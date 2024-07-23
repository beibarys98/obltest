<?php

use common\models\Formula;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions */
/** @var $formula */

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
                <?= Html::a('x', ['delete-formula', 'id' => $f->id, 'test_id' => $test->id],
                    ['class' => 'btn btn-danger'])?>
            <?php else:?>
                <br>
                <?= Html::a('+', ['add-formula', 'id' => $q->id, 't' => 'question'], ['class' => 'btn btn-primary m-1'])?>
            <?php endif;?>

            <div class="d-flex">
                <?= 'a. '?>
                <?php if($q->answer1):?>
                <?= $q->answer1;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer1'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                        <?= Html::a('x', ['delete-formula', 'id' => $f->id, 'test_id' => $test->id],
                            ['class' => 'btn btn-danger',
                                'style' => [
                                    'height' => '40px'
                                ]])?>
                    <?php else:?>
                        <?= Html::a('+', ['add-formula', 'id' => $q->id, 't' => 'answer1'], ['class' => 'btn btn-primary m-1'])?>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'b. '?>
                <?php if($q->answer2):?>
                    <?= $q->answer2;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer2'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                        <?= Html::a('x', ['delete-formula', 'id' => $f->id, 'test_id' => $test->id],
                            ['class' => 'btn btn-danger',
                                'style' => [
                                    'height' => '40px'
                                ]])?>
                    <?php else:?>
                        <?= Html::a('+', ['add-formula', 'id' => $q->id, 't' => 'answer2'], ['class' => 'btn btn-primary m-1'])?>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'c. '?>
                <?php if($q->answer3):?>
                    <?= $q->answer3;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer3'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                        <?= Html::a('x', ['delete-formula', 'id' => $f->id, 'test_id' => $test->id],
                            ['class' => 'btn btn-danger',
                                'style' => [
                                    'height' => '40px'
                                ]])?>
                    <?php else:?>
                        <?= Html::a('+', ['add-formula', 'id' => $q->id, 't' => 'answer3'], ['class' => 'btn btn-primary m-1'])?>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="d-flex">
                <?= 'd. '?>
                <?php if($q->answer4):?>
                    <?= $q->answer4;?>
                <?php else:?>
                    <?php if($f = Formula::findOne(['question_id' => $q->id, 'type' => 'answer4'])):?>
                        <br>
                        <img src="<?= Yii::getAlias('@web') . '/' . str_replace('/app/frontend/web/', '', $f->path) ?>"
                             class="img-thumbnail shadow m-3" style="max-height: 200px; border: 1px solid black;">
                        <?= Html::a('x', ['delete-formula', 'id' => $f->id, 'test_id' => $test->id],
                            ['class' => 'btn btn-danger',
                                'style' => [
                                        'height' => '40px'
                                ]])?>
                    <?php else:?>
                        <?= Html::a('+', ['add-formula', 'id' => $q->id, 't' => 'answer4'], ['class' => 'btn btn-primary m-1'])?>
                    <?php endif;?>
                <?php endif;?>
            </div>
        </div>
        <br>


    <?php endforeach; ?>

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
