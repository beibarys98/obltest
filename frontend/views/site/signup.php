<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

/**
 * @var $model2
 * @var $model3
 * @var $regions
 * @var $schools
 * @var $subjects
 */

use common\models\Region;
use common\models\School;
use common\models\Subject;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

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
                $regions = ArrayHelper::map(Region::find()->all(), 'id', 'region');
                ?>

                <?= $form->field($model2, 'region_id')->dropDownList(
                    $regions,
                    [
                        'prompt' => 'Select Region',
                        'onchange' => 'this.form.submit()'
                    ]
                ) ?>

                <?php
                $schools = [];
                if ($model2->region_id) {
                    $schools = ArrayHelper::map(School::find()->where(['region_id' => $model2->region_id])->all(), 'id', 'school');
                }
                ?>

                <?= $form->field($model2, 'school_id')->dropDownList(
                    $schools,
                    ['prompt' => 'Select School']) ?>

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
