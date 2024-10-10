<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\TestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProvider2 */
/** @var yii\data\ActiveDataProvider $dataProvider3 */
/** @var yii\data\ActiveDataProvider $dataProvider4 */
/** @var yii\data\ActiveDataProvider $dataProvider5 */
/** @var $percentage*/

$this->title = Yii::t('app', Yii::$app->name)
?>
<div class="test-index">

    <h1>Тесттер</h1>

    <div class="p-3 shadow-sm" style="border: 1px solid black; border-radius: 10px;">
        <?php $form = ActiveForm::begin(); ?>

        <div class="d-flex">
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'first')->textInput()->label('Бірінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'second')->textInput()->label('Екінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'third')->textInput()->label('Үшінші') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'good')->textInput()->label('Алғыс хат') ?>
            </div>
            <div style="width: 20%" class="p-1">
                <?= $form->field($percentage, 'participant')->textInput()->label('Сертификат') ?>
            </div>
        </div>
        <br>
        <div class="form-group">
            <?= Html::submitButton('Сақтау', ['class' => 'btn btn-primary',
                'style' => 'width: 100px; text-align: center;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <br>

    <p>
        <?= Html::a(Yii::t('app', 'Жаңа тест'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>
    <div class="row">
        <div class="col-4">

            <h4>Жаңа тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'showHeader' => false,
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::tag('div', $model->title, [
                                'style' => 'display: -webkit-box; -webkit-line-clamp: 3; 
                                    -webkit-box-orient: vertical; overflow: hidden; 
                                    text-overflow: ellipsis; max-height: 4.5em; line-height: 1.5em; width: 120px;']),
                                ['view', 'id' => $model->id], ['title' => $model->title,]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('div', $model->subject->subject, [
                                    'style' => 'width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
                                ])
                                . Html::tag('div', 'Тілдік топ: '.$model-> language, [])
                                . Html::tag('div', 'Нұсқа: ' . $model->version);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d M', strtotime($model->start_time)) . "<br>" .
                                date('H:i:s', strtotime($model->duration))
                            );
                        },
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-4">

            <h4>Дайын тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider2,
                'layout' => "{items}",
                'showHeader' => false,
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::tag('div', $model->title, [
                                'style' => 'display: -webkit-box; -webkit-line-clamp: 3; 
                                    -webkit-box-orient: vertical; overflow: hidden; 
                                    text-overflow: ellipsis; max-height: 4.5em; line-height: 1.5em; width: 120px;']),
                                ['view', 'id' => $model->id], ['title' => $model->title,]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('div', $model->subject->subject, [
                                'style' => 'width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
                            ])
                                . Html::tag('div', 'Тілдік топ: '.$model-> language, [])
                                . Html::tag('div', 'Нұсқа: ' . $model->version);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d M', strtotime($model->start_time)) . "<br>" .
                                date('H:i:s', strtotime($model->duration))
                            );
                        },
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-4">

            <h4>Жарияланған тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider3,
                'layout' => "{items}",
                'showHeader' => false,
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::tag('div', $model->title, [
                                'style' => 'display: -webkit-box; -webkit-line-clamp: 3; 
                                    -webkit-box-orient: vertical; overflow: hidden; 
                                    text-overflow: ellipsis; max-height: 4.5em; line-height: 1.5em; width: 120px;']),
                                ['view', 'id' => $model->id], ['title' => $model->title,]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('div', $model->subject->subject, [
                                    'style' => 'width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
                                ])
                                . Html::tag('div', 'Тілдік топ: '.$model-> language, [])
                                . Html::tag('div', 'Нұсқа: ' . $model->version);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d M', strtotime($model->start_time)) . "<br>" .
                                date('H:i:s', strtotime($model->duration))
                            );
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-6">
            <h4>Аяқталған тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider4,
                'layout' => "{items}",
                'showHeader' => false,
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::tag('div', $model->title, [
                                'style' => 'display: -webkit-box; -webkit-line-clamp: 3; 
                                    -webkit-box-orient: vertical; overflow: hidden; 
                                    text-overflow: ellipsis; max-height: 4.5em; line-height: 1.5em; width: 200px;']),
                                ['view', 'id' => $model->id], ['title' => $model->title,]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('div', $model->subject->subject, [
                                    'style' => 'width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
                                ])
                                . Html::tag('div', 'Тілдік топ: '.$model-> language, [])
                                . Html::tag('div', 'Нұсқа: ' . $model->version);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d M', strtotime($model->start_time)) . "<br>" .
                                date('H:i:s', strtotime($model->duration))
                            );
                        },
                    ],
                ],
            ]); ?>
        </div>

        <div class="col-6">
            <h4>Марапатталған тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider5,
                'layout' => "{items}",
                'showHeader' => false,
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(Html::tag('div', $model->title, [
                                'style' => 'display: -webkit-box; -webkit-line-clamp: 3; 
                                    -webkit-box-orient: vertical; overflow: hidden; 
                                    text-overflow: ellipsis; max-height: 4.5em; line-height: 1.5em; width: 200px;']),
                                ['view', 'id' => $model->id], ['title' => $model->title,]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('div', $model->subject->subject, [
                                    'style' => 'width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
                                ])
                                . Html::tag('div', 'Тілдік топ: '.$model-> language, [])
                                . Html::tag('div', 'Нұсқа: ' . $model->version);
                        },
                    ],
                    [
                        'attribute' => 'id',
                        'label' => 'Файл',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a('Нәтиже.pdf', Url::to(['test/result', 'id' => $model->id]), ['data-pjax' => 0]);
                        }
                    ],
                ],
            ]); ?>
        </div>




    <?php Pjax::end(); ?>

</div>
