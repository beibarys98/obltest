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
/** @var $hasPaid*/

$this->title = $test->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <p>
        <?php
        $isActive ? $class = 'active' : $class = 'disabled';
        if(!$hasPaid){
            echo Html::a(
                Yii::t('app', 'Оплатить'),
                ['payment/pay', 'id' => $test->id],
                ['class' => 'btn btn-primary '.$class]);
        }else{
            echo Html::a(
                Yii::t('app', 'Начать'),
                ['view', 'id' => $test->id],
                [
                    'class' => 'btn btn-success active '.$class,
                    'data' => [
                        'confirm' => Yii::t('app', 'Вы уверены?'),
                        'method' => 'post',
                    ],
                ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $test,
        'attributes' => [
            [
                'attribute' => 'title',
                'label' => Yii::t('app', 'Заголовок')
            ],
            [
                'attribute' => 'subject',
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
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $test->status == 'finished' ? $report : new \yii\data\ArrayDataProvider(),
        'layout' => "{items}",
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'path',
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
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('Сертификат',
                        ['download', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

</div>
