<?php

use common\models\Formula;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var $question */
/** @var $type*/
/** @var $formula*/

\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

        <div style="font-size: 24px;">
            <?= $question->number . '. '; ?>
            <?= $question->question; ?>
            <div class="d-flex">
                <?php switch($type){
                    case 'answer1':
                        echo 'a. ';
                        break;
                    case 'answer2':
                        echo 'b. ';
                        break;
                    case 'answer3':
                        echo 'c. ';
                        break;
                    case 'answer4':
                        echo 'd. ';
                        break;
                }?>
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>

                <?= $form->field($formula, 'file')->fileInput(['class' => 'ms-3'])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
</div>
