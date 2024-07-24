<?php

/** @var yii\web\View $this */
/** @var $test*/

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Obl Test';
?>
<div class="site-index">
    <?= GridView::widget([
        'dataProvider' => $test,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->title, ['detail-view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'subject_id',
                'label' => 'Пән',
                'value' => 'subject.subject'
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
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function ($model) {
                    return Yii::t('app', $model->status);
                }
            ]
        ],
    ]); ?>
</div>
