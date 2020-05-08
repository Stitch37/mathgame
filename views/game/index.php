<?php

use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LevelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уровни';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="levels-index">

    <h1><?=Html::encode($this->title)?></h1>
	
	<?php Pjax::begin(); ?>
	
	<?php
	if (User::isAdmin())
	{
		?>
        <div class="justify-content-between d-flex">
            <p><?=Html::a('Добавить уровень', ['game/create'], ['class' => 'btn btn-yellow'])?></p>
            <p><?=Html::a('Список всех задач', ['tasks/index'], ['class' => 'btn btn-yellow'])?></p>
        </div>
		
		<?php
	}
	?>
	
	<?=GridView::widget([
		'dataProvider' => $dataProvider,
//		'filterModel' => $searchModel,
		'summary' => false,
		'tableOptions' =>
			[
				'class' => 'table-dark table table-striped table-bordered',
			],
		'columns' => [
//			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'name',
				'label' => 'Название',
				'enableSorting' => false,
			],
			[
				'attribute' => 'difficulty',
				'label' => 'Сложность',
				'enableSorting' => false,
			],
			[
				'label' => 'Ссылка',
				'value' => static function ($model) {
					return Html::a('Перейти', ['game/play', 'id' => $model->id,]);
				},
				'format' => 'raw',
			],
			[
				'label' => 'Пройден',
				'value' => static function ($model) {
					return $model->isCompletedByCurrentUser() ? '&#10004;' : '&#10007;';
				},
				'format' => 'raw',
			],
//			['class' => 'yii\grid\ActionColumn'],
		],
	]);?>
	
	<?php Pjax::end(); ?>

</div>
