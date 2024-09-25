<?php

/** @var yii\web\View $this */
/** @var $test*/

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', Yii::$app->name)
?>
<div class="site-index">
    <?= GridView::widget([
        'dataProvider' => $test,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'title',
                'label' => Yii::t('app', 'Заголовок'),
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->title, ['detail-view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'subject_id',
                'label' => Yii::t('app', 'Предмет'),
                'value' => function($model) {
                    // Check the current application language
                    if (Yii::$app->language === 'ru-RU') {
                        return $model->subject->subject_ru; // Show subject in Russian
                    } else {
                        return $model->subject->subject; // Show subject in Kazakh
                    }
                },
            ],
            [
                'attribute' => 'start_time',
                'label' => Yii::t('app', 'Открытие'),
                'value' => function ($model) {
                    return date('d/m H:i', strtotime($model->start_time)); // Short month name
                },
            ],
            [
                'attribute' => 'end_time',
                'label' => Yii::t('app', 'Закрытие'),
                'value' => function ($model) {
                    return date('d/m H:i', strtotime($model->end_time)); // Short month name
                },
            ],
            [
                'attribute' => 'duration',
                'label' => Yii::t('app', 'Длительность'),
                'value' => function ($model) {
                    return date('H:i:s', strtotime($model->duration)); // Short month name
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
