<?php
// views/site/_time_display.php
/** @var $test*/
/** @var $time*/

use yii\helpers\Url;

?>

<div class="d-flex justify-content-center align-items-center">
    <?= $time ? $time : '00:00:00' ?>
    <div class="ms-1">
        <a href="<?php Url::to(['refresh-time', 'id' => $test->id])?>"
           data-method="post" data-pjax="1" style="text-decoration: none;">
            <i class="fa-solid fa-arrows-rotate" style="border: 1px solid black; border-radius: 5px; font-size: 24px;"></i>
        </a>
    </div>
</div>
