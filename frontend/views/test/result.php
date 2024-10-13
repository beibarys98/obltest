<?php

/** @var yii\web\View $this */
/** @var $results*/
/** @var $testDP*/

use yii\grid\GridView;
use yii\widgets\DetailView;

\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <div>
        <?= GridView::widget([
            'dataProvider' => $testDP,
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'title',
                    'label' => 'Атауы'
                ],
                [
                    'attribute' => 'language',
                    'label' => 'Тіл'
                ],
                [
                    'attribute' => 'version',
                    'label' => 'Нұсқа'
                ]
            ],
        ]); ?>
    </div>

    <div>
        <?= GridView::widget([
            'dataProvider' => $results,
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'teacher.name',
                    'label' => 'Есімі',
                ],
                [
                    'attribute' => 'result',
                    'label' => 'Нәтиже',
                ],
            ],
        ]); ?>
    </div>

</div>
