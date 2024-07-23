<?php

use common\models\Formula;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="shadow p-1 mb-3" style="border: 1px solid black; border-radius: 10px;">
        <label for="readonly">Пән</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= $test->subject->subject ?>" readonly>
        <label for="readonly">Статус</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= $test->status ?>" readonly>
        <label for="readonly">Басталуы</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= date('d/m H:i', strtotime($test->start_time)) ?>" readonly>
        <label for="readonly">Аяқталуы</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= date('d/m H:i', strtotime($test->end_time)) ?>" readonly>
    </div>

    <div class="shadow p-1 mb-3 me-5" style="border: 1px solid black; border-radius: 10px; display: inline-block;">
        <?= Html::a(Yii::t('app', 'Формула қосу'),
                ['formula', 'id' => $test->id],
                ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Өзгерту'),
            ['update', 'id' => $test->id],
            ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('app', 'Өшіру'),
            ['delete', 'id' => $test->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                    'method' => 'post',
            ],
        ]) ?>
    </div>


        <div class="shadow p-1 mb-3 me-5" style="border: 1px solid black; border-radius: 10px; display: inline-block;">
            <?php if($test->status != 'finished'):?>
            <?= $test->status == 'new'
                ? Html::a(Yii::t('app', 'Дайын') ,
                    ['ready', 'id' => $test->id],
                    ['class' => 'btn btn-success'])
                : ($test->status == 'public'
                    ? Html::a(Yii::t('app', 'Алып тастау') ,
                        ['ready', 'id' => $test->id],
                        ['class' => 'btn btn-secondary'])
                    : Html::a(Yii::t('app', 'Жариялау') ,
                        ['publish', 'id' => $test->id],
                        ['class' => 'btn btn-success']))?>
            <?php endif;?>
            <?php if($test->status == 'public' || $test->status == 'finished'):?>
                <?= $test->status == 'public'
                    ? Html::a(Yii::t('app', 'Аяқтау') ,
                        ['end', 'id' => $test->id],
                        ['class' => 'btn btn-danger'])
                    : ($test->status == 'finished'
                        ? Html::a(Yii::t('app', 'Нәтиже') ,
                            ['result', 'id' => $test->id],
                            ['class' => 'btn btn-secondary', 'target' => '_blank'])
                        : null) ?>
            <?php endif;?>
        </div>

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


</div>
