<?php

use common\models\Formula;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $test */
/** @var $report*/
/** @var $certificate*/
/** @var $isActive*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            Yii::t('app', 'Бастау'),
            ['view', 'id' => $test->id],
            [
                'class' => $isActive ? 'btn btn-success active' : 'btn btn-success disabled',
                'data' => [
                    'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                    'method' => 'post',
                ],
            ]
        ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $test,
        'attributes' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы'
            ],
            [
                'attribute' => 'subject.subject',
                'label' => 'Пән'
            ],
            [
                'attribute' => 'start_time',
                'label' => 'Басталуы'
            ],
            [
                'attribute' => 'end_time',
                'label' => 'Аяқталуы'
            ],
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $report,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'path',
                'label' => 'Қатемен жұмыс',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('Қатемен жұмыс',
                        ['download', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $certificate,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'path',
                'label' => 'Сертификат',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('Сертификат',
                        ['download', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

</div>
