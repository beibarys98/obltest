<?php

/** @var yii\web\View $this */
/** @var $results*/
/** @var $testDP*/

use common\models\TestTaker;
use yii\grid\GridView;

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
                    'attribute' => 'subject_id',
                    'label' => 'Пән',
                    'value' => 'subject.subject'
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
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'teacher.name',
                    'label' => 'Есімі',
                ],
                [
                    'attribute' => 'teacher.school',
                    'label' => 'Мекеме',
                ],
                [
                    'attribute' => 'result',
                    'label' => 'Нәтиже',
                ],
            ],
        ]); ?>
    </div>

</div>
