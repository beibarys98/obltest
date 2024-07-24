<?php

use common\models\ResultPdf;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\ResultPdfSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Нәтижелер');
?>
<div class="result-pdf-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'test_id',
                'label' => 'Пән',
                'value' => 'test.subject.subject'
            ],
            [
                'attribute' => 'test_id',
                'label' => 'Тест',
                'value' => 'test.title'
            ],
            [
                'attribute' => 'test_id',
                'label' => 'Басталуы',
                'value' => function ($model) {
                    return date('d/m H:i', strtotime($model->test->start_time)); // Short month name
                },
            ],
            [
                'attribute' => 'test_id',
                'label' => 'Аяқталуы',
                'value' => function ($model) {
                    return date('d/m H:i', strtotime($model->test->end_time)); // Short month name
                },
            ],
            [
                'attribute' => 'path',
                'label' => 'Файл',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('Нәтиже.pdf', Url::to(['test/result', 'id' => $model->test_id]), ['data-pjax' => 0]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
