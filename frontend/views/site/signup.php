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

$this->title = 'Тіркелу';
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model2, 'name')->textInput(['autofocus' => true])->label('Есіміңіз') ?>

                <?= $form->field($model, 'username')->textInput()->label('Логин') ?>

                <?php
                $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
                ?>

                <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
                    'data' => $schools,
                    'options' => ['placeholder' => 'Мектеп таңдаңыз'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Мектеп');?>

                <?php
                $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
                ?>

                <?= $form->field($model2, 'subject_id')->dropDownList(
                    $subjects,
                    ['prompt' => 'Пән таңдаңыз'])->label('Пән') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Құпия сөз') ?>

                <div class="form-group">
                    <?= Html::submitButton('Тіркелу', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
