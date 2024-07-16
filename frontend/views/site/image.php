<?php

/** @var yii\web\View $this */
/** @var $imgSrc*/
/** @var $teacher*/

use yii\bootstrap5\Html;

$this->title = 'Obl Test';
?>
<div style="position: relative; text-align: center;">
    <img src="<?= $imgSrc ?>" alt="Certificate" width="100%" />
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'),
            [
                'class' => 'btn btn-success rounded-circle shadow',
                'style' => 'position: fixed;
                                bottom: 10%;
                                right: 9%;
                                z-index: 1000;
                                width: 107px;
                                height: 107px;'
            ]) ?>
    </div>
</div>
