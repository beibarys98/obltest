<?php

use common\models\Teacher;
use common\models\TestTaker;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Мұғалімдер');
?>
<div class="teacher-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Жаңа мұғалім'), ['create'], ['class' => 'btn btn-success w-100']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            'id',
            [
                'attribute' => 'username',
                'label' => 'Логин',
                'value' => 'user.username',
            ],
            [
                'attribute' => 'name',
                'label' => 'Т.А.Ә.'
            ],
            [
                'attribute' => 'school',
                'label' => 'Мекеме атауы'
            ],
            [
                'attribute' => 'subject',
                'label' => 'Пән',
                'value' => 'subject.subject',
            ],
            [
                'attribute' => 'language',
                'label' => 'Тест тапсыру тілі'
            ],
            [
                'format' => 'raw',  // Ensures the link is not HTML-encoded
                'value' => function ($model) {
                    // Find the corresponding TestTaker model based on teacher_id
                    $testTaker = TestTaker::find()->andWhere(['teacher_id' => $model->id])->one();

                    // Ensure the TestTaker exists before creating the link
                    if ($testTaker !== null) {
                        return Html::a('edit', ['test-taker/update', 'id' => $testTaker->id]);
                    }

                    // Optionally, return a placeholder or null if no TestTaker is found
                    return null;  // Or return 'No TestTaker';
                }
            ],


        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
