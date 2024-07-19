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
/** @var $file*/
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
                    'confirm' => Yii::t('app', 'Вы уверены что хотите начать?'),
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
        'dataProvider' => $file,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'path',
                'label' => 'Файлдар',
                'format' => 'raw',
                'value' => function ($model) {
                    if (preg_match('/\.pdf$/i', $model->path)) {
                        $text = 'Қатемен жұмыс';
                    } elseif (preg_match('/\.(jpeg|jpg)$/i', $model->path)) {
                        $text = 'Сертификат';
                    } else {
                        $text = $model->path; // Default to the path if no match
                    }
                    return Html::a($text,
                        ['download', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

</div>
