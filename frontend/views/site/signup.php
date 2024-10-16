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

            <?= $form->field($model2, 'school')->textInput()->label(Yii::t('app', 'Наименование организаций'));?>

            <?php
            // Determine which field to use based on the current language
            $subjectField = (Yii::$app->language === 'ru-RU') ? 'subject_ru' : 'subject';

            // Map the appropriate field for the subjects
            $subjects = ArrayHelper::map(Subject::find()->all(), 'id', $subjectField);
            ?>

        <?= $form->field($model2, 'subject_id', ['labelOptions' => ['label' => Yii::t('app', 'Предмет')]])
            ->widget(Select2::classname(),
        [
            'data' => $subjects,
            'options' => [
                'placeholder' => '',
                'style' => ['width' => '100%'], // Ensure full width of the select box
            ],
            'pluginOptions' => [
                'allowClear' => true, // Allow clearing the selection
                'dropdownAutoWidth' => true, // Adjust dropdown width automatically
                'maximumInputLength' => 20, // Optionally limit search length
            ],
        ]); ?>


        <?php
            $languages = [
                'kz' => Yii::t('app', 'казахский'),
                'ru' => Yii::t('app', 'русский'),
            ];
            ?>

            <?= $form->field($model2, 'language')->dropDownList(
                $languages,
                ['prompt' => ''])
                ->label(Yii::t('app', 'Язык сдачи теста'))?>

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
                <div class="accordion-collapse collapse show" id="collapseOne">
                    <div class="accordion-body" style="font-size: 16px;">
                        <!-- YouTube video embed -->
                        <iframe width="100%" height="270px" src="<?= Yii::t('app', 'https://www.youtube.com/embed/ZYeX8mDePPI') ?>"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                        <br>
                        <br>
                        1.	<?= Yii::t('app', 'Вопросы олимпиады: По предмету – 50 вопросов;') ?> <br>
                        2.	<?= Yii::t('app', 'Время тестирования – 60 минут. По предметам математика, физика, химия – 120 минут. (по истечении времени тестирование автоматически закрывается);') ?> <br>
                        3.	<?= Yii::t('app', 'Из предложенных 4 ответов нужно выбрать 1 правильный ответ;') ?> <br>
                        4.	<?= Yii::t('app', '1 правильный ответ – 1 балл;') ?> <br>
                        5.	<?= Yii::t('app', 'Участник должен указать полные сведения о себе (Ф.И.О. по удостоверению личности, указать название города, района, наименование школы);') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
