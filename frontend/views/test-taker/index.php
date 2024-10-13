<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\TestTakerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $purpose */

$this->title = 'Қатысушылар';
?>
<div class="test-taker-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'username',
                'label' => 'Логин',
                'value' => 'teacher.user.username'
            ],
            [
                'attribute' => 'name',
                'label' => 'Т.А.Ә.',
                'value' => 'teacher.name'
            ],
            [
                'attribute' => 'subject',
                'label' => 'Пән',
                'value' => 'test.subject.subject'
            ],
            [
                'attribute' => 'language',
                'label' => 'Тіл',
                'value' => 'test.language'
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Уақыт',
                'format' => 'raw',
                'value' => function ($model) {
                    $output = '';

                    // Check if created_at is set, otherwise add an empty line with <br>
                    if (isset($model->teacher->payment->created_at)) {
                        $created_at = new \DateTime($model->teacher->payment->created_at);
                        $output .= $created_at->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    // Check if start_time is set, otherwise add an empty line with <br>
                    if (isset($model->start_time)) {
                        $start_time = new \DateTime($model->start_time);
                        $output .= $start_time->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    // Check if end_time is set, otherwise add an empty line with <br>
                    if (isset($model->end_time)) {
                        $end_time = new \DateTime($model->end_time);
                        $output .= $end_time->format('d/m/y H:i:s') . "<br>";
                    } else {
                        $output .= "--- <br>";  // Empty with <br> if null
                    }

                    return $output;
                },
            ],
            [
                'attribute' => 'result',
                'label' => 'Нәтиже',
                'value' => function ($model) {
                    return isset($model->teacher->result->result) ? $model->teacher->result->result : '---';
                },
            ],
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $file = \common\models\File::find()
                        ->where(['teacher_id' => $model->teacher->id])
                        ->andWhere(['like', 'path', '%.jpeg', false])
                        ->one();
                    $file2 = \common\models\File::find()
                        ->where(['teacher_id' => $model->teacher->id])
                        ->andWhere(['like', 'path', '%.pdf', false])
                        ->one();

                    return ( isset($model->teacher->payment->payment)
                            ? Html::a('Квитанция', Url::to(['payment/receipt', 'id' => $model->teacher->payment->id]),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---' )
                    . '<br>'
                    . ($file
                            ? Html::a('Сертификат', Url::to(['test-taker/download',
                                'id' => $model->teacher->id, 'type' => 'jpeg']),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---')
                        . '<br>'
                    . ($file2
                            ? Html::a('Қатемен жұмыс', Url::to(['test-taker/download',
                                'id' => $model->teacher->id, 'type' => 'pdf']),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ])
                            : '---');
                }
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
