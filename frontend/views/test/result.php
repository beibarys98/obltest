<?php

/** @var yii\web\View $this */
/** @var $results*/

use yii\grid\GridView;

\yii\web\YiiAsset::register($this);
?>
<div class="test-view">
    <?= GridView::widget([
        'dataProvider' => $results,
        'layout' => "{items}",
        'columns' => [
            'teacher.name',
            'result'
        ],
    ]); ?>
</div>
