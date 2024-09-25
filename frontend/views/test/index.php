<?php

use common\models\Test;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\TestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProvider2 */
/** @var yii\data\ActiveDataProvider $dataProvider3 */
/** @var yii\data\ActiveDataProvider $dataProvider4 */

$this->title = Yii::t('app', Yii::$app->name)
?>
<div class="test-index">

    <h1>Тесттер</h1>

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
                            return Html::a($model->title, ['view', 'id' => $model->id]);
                        },
                    ],
                    [
                        'value' => 'subject.subject',
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d/m H:i', strtotime($model->start_time)) . "<br>" .
                                date('d/m H:i', strtotime($model->end_time)) . "<br>" .
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
                            return Html::a($model->title, ['view', 'id' => $model->id]);
                        },
                    ],
                    [
                        'value' => 'subject.subject',
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d/m H:i', strtotime($model->start_time)) . "<br>" .
                                date('d/m H:i', strtotime($model->end_time)) . "<br>" .
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
                            return Html::a($model->title, ['view', 'id' => $model->id]);
                        },
                    ],
                    [
                        'value' => 'subject.subject',
                    ],
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            return nl2br(
                                date('d/m H:i', strtotime($model->start_time)) . "<br>" .
                                date('d/m H:i', strtotime($model->end_time)) . "<br>" .
                                date('H:i:s', strtotime($model->duration))
                            );
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="row">

        <h4>Аяқталған тесттер</h4>

        <?= GridView::widget([
            'dataProvider' => $dataProvider4,
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'title',
                    'label' => 'Атауы',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->title, ['view', 'id' => $model->id]);
                    },
                ],
                [
                    'attribute' => 'subject_id',
                    'value' => 'subject.subject',
                    'label' => 'Пән'
                ],
                [
                    'attribute' => 'start_time',
                    'label' => 'Ашылуы',
                    'value' => function ($model) {
                        return date('d/m H:i', strtotime($model->start_time)); // Short month name
                    },
                ],
                [
                    'attribute' => 'end_time',
                    'label' => 'Жабылуы',
                    'value' => function ($model) {
                        return date('d/m H:i', strtotime($model->end_time)); // Short month name
                    },
                ],
                [
                    'attribute' => 'duration',
                    'label' => 'Узақтығы',
                    'value' => function ($model) {
                        return date('H:i:s', strtotime($model->duration)); // Short month name
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
