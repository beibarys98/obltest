<?php

$this->title = Yii::t('app', 'Ничего не найдено');
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <div class="mt-5" style="margin: 0 auto; width: 500px;">

        <div class="btn disabled w-100" style="font-size: 24px;">
            <?= Yii::t('app', 'Ничего не найдено') ?>
        </div>

</div>
