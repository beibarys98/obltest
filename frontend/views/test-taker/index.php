<?php

use common\models\TestTaker;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $test */

$this->title = $test->title;
?>
<div class="test-taker-index">

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
                        ['test/end', 'id' => $test->id],
                        [
                            'class' => 'btn btn-warning',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);

                    echo Html::a(Yii::t('app', 'Тест') ,
                        ['/test/view', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);

                }else if($test->status == 'finished'){
                    echo Html::a(Yii::t('app', 'Марапаттау') ,
                        ['test/present', 'id' => $test->id],
                        [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                            ]
                        ]);

                    echo Html::a(Yii::t('app', 'Тест') ,
                        ['/test/view', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);

                }else if($test->status == 'certificated'){
                    echo Html::a(Yii::t('app', 'Нәтиже') ,
                        ['/test/result', 'id' => $test->id],
                        ['class' => 'btn btn-success', 'target' => '_blank']);

                    echo Html::a(Yii::t('app', 'Тест') ,
                        ['/test/view', 'id' => $test->id],
                        ['class' => 'btn btn-primary ms-1']);
                }
                ?>

                <?= Html::a(Yii::t('app', 'Өшіру'),
                    ['test/delete', 'id' => $test->id],
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

    <div class="row w-100">
        <div class="col-3 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'receipts', 'id' => $test->id]) ?>" class="btn btn-primary w-100">
                Квитанцияларды жүктеп алу
            </a>
        </div>
        <div class="col-3 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'certificates', 'id' => $test->id]) ?>" class="btn btn-warning w-100">
                Сертификаттарды жүктеп алу
            </a>
        </div>
        <div class="col-3 p-1">
            <a href="<?= Url::to(['test/download-zip', 'type' => 'reports', 'id' => $test->id]) ?>" class="btn btn-danger w-100">
                Қатемен жұмыстарды жүктеп алу
            </a>
        </div>
        <div class="col-3 p-1">
            <a href="<?= Url::to(['test/result', 'id' => $test->id]) ?>" class="btn btn-success w-100" target="_blank">
                Нәтиже
            </a>
        </div>
    </div>

    <br>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            [
                'attribute' => 'teacher_id',
                'label' => 'ID'
            ],
            [
                'attribute' => 'username',
                'label' => 'Логин',
                'value' => 'teacher.user.username'
            ],
            [
                'attribute' => 'name',
                'label' => 'Т.А.Ә.',
                'value' => 'teacher.name'
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Уақыт',
                'format' => 'raw',
                'value' => function ($model) {
                    $output = '';

                    // Check if created_at is set, otherwise add an empty line with <br>
                    if (isset($model->teacher->payment->created_at)) {
                        $created_at = new \DateTime($model->teacher->payment->created_at);
                        $output .= $created_at->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    // Check if start_time is set, otherwise add an empty line with <br>
                    if (isset($model->start_time)) {
                        $start_time = new \DateTime($model->start_time);
                        $output .= $start_time->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    // Check if end_time is set, otherwise add an empty line with <br>
                    if (isset($model->end_time)) {
                        $end_time = new \DateTime($model->end_time);
                        $output .= $end_time->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    return $output;
                },
            ],
            [
                'attribute' => 'result',
                'label' => 'Нәтиже',
                'value' => function ($model) {
                    return isset($model->teacher->result->result) ? $model->teacher->result->result : '---';
                },
            ],
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $file = \common\models\File::find()
                        ->where(['teacher_id' => $model->teacher->id])
                        ->andWhere(['like', 'path', '%.jpeg', false])
                        ->one();
                    $file2 = \common\models\File::find()
                        ->where(['teacher_id' => $model->teacher->id])
                        ->andWhere(['like', 'path', '%.pdf', false])
                        ->one();

                    return ( isset($model->teacher->payment->payment)
                            ? Html::a('Квитанция', Url::to(['payment/receipt', 'id' => $model->teacher->payment->id]),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---' )
                        . '<br>'
                        . ($file
                            ? Html::a('Сертификат', Url::to(['test-taker/download',
                                'id' => $model->teacher->id, 'type' => 'jpeg']),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---')
                        . '<br>'
                        . ($file2
                            ? Html::a('Қатемен жұмыс', Url::to(['test-taker/download',
                                'id' => $model->teacher->id, 'type' => 'pdf']),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---');
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, TestTaker $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
