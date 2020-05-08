<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Инструкция';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?=Html::encode($this->title)?></h1>

    <p>
        Страница-инструкция в пользованию сайтом. Редактировать тут:
    </p>

    <code><?=__FILE__?></code>
</div>
