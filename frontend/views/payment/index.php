<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\PaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $purpose*/

$this->title = Yii::t('app', 'Төлемдер');

?>
<div class="payment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="p-3 shadow-sm" style="border: 1px solid black; border-radius: 10px;">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($purpose, 'purpose')->textInput()->label('Назначение платежа') ?>

        <?= $form->field($purpose, 'cost')->textInput()->label('Сумма') ?>

        <div class="form-group">
            <?= \yii\helpers\Html::submitButton('Сақтау', ['class' => 'btn btn-primary', 'style' => 'width: 100px; text-align: center;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <br>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'username',
                'label' => 'Логин',
                'value' => 'teacher.user.username'
            ],
            [
                'attribute' => 'name',
                'label' => 'Т.А.Ә.',
                'value' => 'teacher.name',
            ],
            [
                'attribute' => 'subject',
                'label' => 'Пән',
                'value' => 'test.subject.subject',
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Уақыты',
                'format' => 'raw',
                'value' => function ($model) {
                    return date('d/m/y H:i:s', strtotime($model->created_at));
                },
            ],
            [
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('Квитанция', ['payment/receipt', 'id' => $model->id], [
                        'target' => '_blank',
                        'data-pjax' => '0', // Disable PJAX for this link
                    ]);
                }
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
