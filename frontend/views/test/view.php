<?php

use common\models\Answer;
use common\models\ResultPdf;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $questions*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <div class="shadow-sm p-3" style="border: 1px solid black; border-radius: 10px; margin: 0 auto; width: 600px;">
        <label for="readonly">Атауы</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= $test->title ?>" readonly>
        <div class="row">
            <div class="col-4">
                <label for="readonly">Пән</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= $test->subject->subject ?>" readonly>
            </div>
            <div class="col-4">
                <label for="readonly">Тест тапсыру тілі</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= $test->language ?>" readonly>
            </div>
            <div class="col-4">
                <label for="readonly">Нұсқа</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= $test->version ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <label for="readonly">Ашылуы</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= date('d/m/y H:i:s', strtotime($test->start_time)) ?>" readonly>
            </div>
            <div class="col-4">
                <label for="readonly">Жабылуы</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= date('d/m/y H:i:s', strtotime($test->end_time)) ?>" readonly>
            </div>
            <div class="col-4">
                <label for="readonly">Узақтығы</label>
                <input id="readonly" class="form-control" type="text" placeholder="<?= date('H:i:s', strtotime($test->duration)) ?>" readonly>
            </div>
        </div>
        <label for="readonly">Статус</label>
        <input id="readonly" class="form-control" type="text" placeholder="<?= Yii::t('app', $test->status) ?>" readonly>

        <br>

        <div class="d-flex justify-content-center">
            <div class="shadow-sm p-1" style="border: 1px solid black; border-radius: 10px; display: inline-block;">
                <?php
                if($test->status == 'new'){
                    echo Html::a(Yii::t('app', 'Дайын') ,
                        ['ready', 'id' => $test->id],
                        ['class' => 'btn btn-success']);
                }else if($test->status == 'ready'){
                    echo Html::a(Yii::t('app', 'Жариялау'),
                        ['publish', 'id' => $test->id],
                        [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);
                }else if($test->status == 'public'){
                    echo Html::a(Yii::t('app', 'Аяқтау') ,
                        ['end', 'id' => $test->id],
                        [
                            'class' => 'btn btn-warning',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);

                    echo Html::a(Yii::t('app', 'Қатысушылар') ,
                        ['/test-taker/index', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);

                }else if($test->status == 'finished'){
                    echo Html::a(Yii::t('app', 'Марапаттау') ,
                        ['present', 'id' => $test->id],
                        [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);
                    echo Html::a(Yii::t('app', 'Қайта жариялау') ,
                        ['publish', 'id' => $test->id],
                        [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);

                    echo Html::a(Yii::t('app', 'Қатысушылар') ,
                        ['/test-taker/index', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);

                }else if($test->status == 'certificated'){
                    echo Html::a(Yii::t('app', 'Нәтиже') ,
                        ['/test/result', 'id' => $test->id],
                        ['class' => 'btn btn-success', 'target' => '_blank']);

                    echo Html::a(Yii::t('app', 'Қатысушылар') ,
                        ['/test-taker/index', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);
                }
                ?>

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
        </div>
    </div>

    <br>

    <div style="font-size: 24px;">
        <?php $number = 1; ?>
        <?php foreach ($questions as $q): ?>
            <?= Html::a('+', ['add-formula', 'id' => $q->id, 'type' => 'question'], [
                'class' => 'btn btn-primary',
            ]) ?>
            <?= $number++ . '. '; ?>
            <?php if ($q->formula): ?>
                <!-- Display the formula image if it exists -->
                <?= Html::img(Url::to('@web/' . $q->formula)) ?>
            <?php else: ?>
                <!-- Display the question text if no formula exists -->
                <?= $q->question; ?>
            <?php endif; ?>
            <br>
            <?php
            $answers = Answer::find()
                ->andWhere(['question_id' => $q->id])
                ->all();
            $alphabet = range('A', 'Z'); // Array of alphabet letters
            $index = 0; // Initialize index for letters
            ?>
            <?php foreach ($answers as $a): ?>
                <?= Html::a('+', ['add-formula', 'id' => $a->id, 'type' => 'answer'], [
                    'class' => 'btn btn-secondary',
                ]) ?>
                <?php if ($a->formula): ?>
                    <!-- Display the formula image if it exists for the answer -->
                    <?php if ($a->id == $q->correct_answer): ?>
                        <strong><?= $alphabet[$index++] . '. '?></strong>
                        <?= Html::img(Url::to('@web/' . $a->formula)) ?>
                        <br>
                    <?php else: ?>
                        <?= $alphabet[$index++] . '. ' ?>
                        <?= Html::img(Url::to('@web/' . $a->formula)) ?>
                        <br>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Display the answer text if no formula exists -->
                    <?php if ($a->id == $q->correct_answer): ?>
                        <strong><?= $alphabet[$index++] . '. ' . $a->answer; ?></strong><br>
                    <?php else: ?>
                        <?= $alphabet[$index++] . '. ' . $a->answer; ?><br>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
    </div>

</div>
