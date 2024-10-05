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

    <div class="mt-5" style="margin: 0 auto; width: 500px;">
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
                <?= Html::submitButton(Yii::t('app', 'Регистрация'), ['class' => 'btn btn-secondary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="mt-5" style="width: 500px; margin: 0 auto;">
        <div class="accordion shadow-sm" style="font-size: 24px;">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        <?= Yii::t('app', 'Инструкция') ?>
                    </button>
                </h2>
                <div class="accordion-collapse collapse" id="collapseOne">
                    <div class="accordion-body" style="font-size: 16px;">
                        1.	<?= Yii::t('app', 'Вопросы олимпиады: По предмету – 50 вопросов;') ?> <br>
                        2.	<?= Yii::t('app', 'Время тестирования – 120 минут (по истечении времени тестирование автоматически закрывается);') ?> <br>
                        3.	<?= Yii::t('app', 'Из предложенных 4 ответов нужно выбрать 1 правильный ответ;') ?> <br>
                        4.	<?= Yii::t('app', '1 правильный ответ – 1 балл;') ?> <br>
                        5.	<?= Yii::t('app', 'Участник должен указать полные сведения о себе (Ф.И.О. по удостоверению личности, указать название города, района, наименование школы);') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
