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

$this->title = Yii::t('app', 'Тесттер');
?>
<div class="test-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                        'label' => 'Басталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->start_time)); // Short month name
                        },
                    ],
                    [
                        'attribute' => 'end_time',
                        'label' => 'Аяқталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->start_time)); // Short month name
                        },
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-4">

            <h4>Өңделген тесттер</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider2,
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
                        'label' => 'Басталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->start_time)); // Short month name
                        },
                    ],
                    [
                        'attribute' => 'end_time',
                        'label' => 'Аяқталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->start_time)); // Short month name
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
                        'label' => 'Басталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->start_time)); // Short month name
                        },
                    ],
                    [
                        'attribute' => 'end_time',
                        'label' => 'Аяқталуы',
                        'value' => function ($model) {
                            return date('d/m H:i', strtotime($model->end_time)); // Short month name
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
                    'label' => 'Басталуы',
                    'value' => function ($model) {
                        return date('d/m H:i', strtotime($model->start_time)); // Short month name
                    },
                ],
                [
                    'attribute' => 'end_time',
                    'label' => 'Аяқталуы',
                    'value' => function ($model) {
                        return date('d/m H:i', strtotime($model->start_time)); // Short month name
                    },
                ],
            ],
        ]); ?>
    </div>


    <?php Pjax::end(); ?>

</div>
