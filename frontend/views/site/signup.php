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

$this->title = 'Signup';
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model2, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'username')->textInput() ?>

                <?php
                $schools = ArrayHelper::map(School::find()->all(), 'id', 'name');
                ?>

                <?= $form->field($model2, 'school_id')->widget(Select2::classname(), [
                    'data' => $schools,
                    'options' => ['placeholder' => 'Select School'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>

                <?php
                $subjects = ArrayHelper::map(Subject::find()->all(), 'id', 'subject');
                ?>

                <?= $form->field($model2, 'subject_id')->dropDownList(
                    $subjects,
                    ['prompt' => 'Select Subject']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
