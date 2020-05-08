<?php

/* @var $this View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->registerCsrfMetaTags() ?>
    <title><?=Html::encode($this->title)?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
//            'class' => 'navbar-inverse navbar-fixed-top',
			'class' => 'navbar navbar-expand-lg navbar-dark bg-dark fixed-top',
		],
	]);
	echo Nav::widget([
//        'options' => ['class' => 'navbar-nav navbar-right'],
		'options' => ['class' => 'justify-content-end ', 'style' => 'width:100%;'],
		'items' => [
			['label' => 'На главную', 'url' => ['/']],
			['label' => 'К игре', 'url' => ['game/index']],

//			[
//				'label' => 'Обратная связь',
//				'url' => ['site/contact'],
//			],
//			['label' => 'Помогатор', 'url' => ['/site/about']],
//            ['label' => 'Contact', 'url' => ['/site/contact']],
//			Yii::$app->user->isGuest ? ['label' => 'Войти', 'url' => ['/site/login']] : (
//				'<li class="nav-item">'
//				. Html::beginForm(['/site/logout'], 'post')
//				. Html::submitButton(
//					'Выйти',
//					['class' => 'btn nav-link logout']
//				)
//				. Html::endForm()
//				. '</li>'
//			)
			!Yii::$app->user->isGuest ?
				[
					'label' => Yii::$app->user->getIdentity()->username,
//					'url' => ['user/view', 'id' => Yii::$app->user->id],
					'items' =>
						[
							['label' => 'Профиль', 'url' => ['/user/view', 'id' => Yii::$app->user->getId()]],
							['label' => 'Выйти', 'url' => ['/site/logout']],
						]
				] : ['label' => 'Войти', 'url' => ['/site/login']],
		],
	]);
	NavBar::end();
	?>

    <div class="container" id="main-container">
		<?=yii\bootstrap4\Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		])?>
		<?=Alert::widget()?>
		<?=$content?>
    </div>
</div>

<footer class="footer bg-dark">
    <div class="container d-flex justify-content-between">
        <p>&copy; &laquo;Храм задач&raquo; <?=date('Y')?></p>
        <p><?=Html::a('Обратная связь', ['site/contact'], ['class' => 'btn btn-primary'])?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
