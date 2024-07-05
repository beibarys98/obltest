<?php

use common\models\Test;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\TestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProvider2 */
/** @var yii\data\ActiveDataProvider $dataProvider3 */

$this->title = Yii::t('app', 'Тесты');
?>
<div class="test-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить тест'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>
    <div class="row">
        <div class="col-4">

            <h4>Загрузите формулы</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'columns' => [

                    'id',
                    [
                        'attribute' => 'subject_id',
                        'value' => 'subject.subject',
                        'label' => 'Предмет'
                    ],
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
        <div class="col-4">

            <h4>Готовые к публикаций</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider2,
                'layout' => "{items}",
                'columns' => [

                    'id',
                    [
                        'attribute' => 'subject_id',
                        'value' => 'subject.subject',
                        'label' => 'Предмет'
                    ],
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
        <div class="col-4">

            <h4>Опубликованные</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider3,
                'layout' => "{items}",
                'columns' => [

                    'id',
                    [
                        'attribute' => 'subject_id',
                        'value' => 'subject.subject',
                        'label' => 'Предмет'
                    ],
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
    </div>


    <?php Pjax::end(); ?>

</div>
