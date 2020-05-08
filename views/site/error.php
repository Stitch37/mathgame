<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?=Html::encode($this->title)?></h1>

    <div class="alert alert-danger">
		<?=nl2br(Html::encode($message))?>
    </div>
    <p>
        Указанные выше ошибка произошла пока сервер обрабатывал ваш запрос.
    </p>
    <p>
        Если вы считаете, что это серверная ошибка, пожалуйста, обратитесь в техподдержку. Спасибо.
    </p>

</div>
