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
                'label' => 'Заголовок',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>
</div>
