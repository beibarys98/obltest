<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var SignupForm $model */

/**
 * @var $model2
 * @var $model3
 * @var $regions
 * @var $schools
 * @var $subjects
 */

use common\models\School;
use common\models\Subject;
use frontend\models\SignupForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Регистрация');
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model2, 'name')->textInput(['autofocus' => true])->label(Yii::t('app', 'Ф.И.О.')) ?>

                <?= $form->field($model, 'username')->textInput()->label('Логин') ?>

                <?php
                $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
                ?>

                <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
                    'data' => $schools,
                    'options' => ['placeholder' => Yii::t('app', 'Выберите школу')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Yii::t('app', 'Школа'));?>

                <?php
                // Determine which field to use based on the current language
                $subjectField = (Yii::$app->language === 'ru-RU') ? 'subject_ru' : 'subject';

                // Map the appropriate field for the subjects
                $subjects = ArrayHelper::map(Subject::find()->all(), 'id', $subjectField);
                ?>

                <?= $form->field($model2, 'subject_id')->dropDownList(
                    $subjects,
                    ['prompt' => Yii::t('app', 'Выберите предмет')])->label(Yii::t('app', 'Предмет')) ?>

                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Пароль')) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Регистрация'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
