<?php

use common\models\Subject;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\TestTakerSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-taker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php
    $subjectField = (Yii::$app->language === 'ru-RU') ? 'subject_ru' : 'subject';
    $subjects = ArrayHelper::map(Subject::find()->all(), 'id', $subjectField);
    ?>

    <?= $form->field($model, 'subject')->dropDownList(
        $subjects,
        ['prompt' => Yii::t('app', 'Выберите предмет')])
        ->label(Yii::t('app', 'Предмет')) ?>

    <?php
    $languages = [
        'kz' => Yii::t('app', 'казахская группа'),
        'ru' => Yii::t('app', 'русская группа'),
    ];
    ?>

    <?= $form->field($model, 'language')->dropDownList(
        $languages,
        ['prompt' => Yii::t('app', 'Выберите языковую группу')])
        ->label(Yii::t('app', 'Языковая группа'))?>

    <div class="form-group mt-3 mb-3">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
