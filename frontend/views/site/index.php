<?php

/** @var yii\web\View $this */
/** @var $test*/

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', Yii::$app->name)
?>
<div class="site-index">
    <div class="mt-5" style="width: 500px; margin: 0 auto;">
        <?= GridView::widget([
            'dataProvider' => $test,
            'layout' => "{items}",
            'showHeader' => false,
            'tableOptions' => [
                'class' => 'table table-bordered shadow-sm',
                'style' => 'border-radius: 10px; overflow: hidden;'
            ],
            'columns' => [
                [
                    'attribute' => 'title',
                    'label' => Yii::t('app', 'Заголовок'),
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center'], // Center the content
                    'value' => function ($model) {
                        return Html::a($model->title, ['detail-view', 'id' => $model->id], [
                            'class' => 'btn w-100',
                            'style' => 'font-size: 24px;' // Set font size to 24px
                        ]);
                    },
                ],
            ],
        ]); ?>


    </div>


</div>
