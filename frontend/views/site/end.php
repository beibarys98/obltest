<?php

/** @var yii\web\View $this */
/** @var $pdfUrl*/
/** @var $imgSrc*/

use yii\bootstrap5\Html;

$this->title = 'Obl Test';
?>
<div class="site-index">

    <div class="row">
        <div class="col-6">
            <embed src="<?= $pdfUrl ?>" type="application/pdf" width="100%" height="600px" />
        </div>
        <div class="col-6">
            <img src="<?= $imgSrc ?>" alt="Certificate" width="100%" />
            <br><br>
            <div style="text-align: center">
                <?= Html::a('Скачать Сертификат', ['certificate'], ['class' => 'btn btn-primary'])?>
            </div>
        </div>
    </div>

</div>
