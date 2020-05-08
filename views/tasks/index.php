<?php

use app\models\Levels;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
		<?=Html::a('Создать задачу', ['create'], ['class' => 'btn btn-yellow'])?>
    </p>
	
	<?php Pjax::begin(); ?>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<?=GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'tableOptions' =>
			[
				'class' => 'table-dark table table-striped table-bordered',
			],
		'rowOptions' => static function ($model) {
			return ['data-id' => $model->id];
		},
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'level',
				'value' => static function ($data) {
					return Levels::findOne($data->level)->name;
				},
				'filter' => ArrayHelper::map(Levels::find()->orderBy('id')->select('id, name')->all(), 'id', 'name'),
			],
			'text:ntext',
			'name:ntext',
			'reward',
			'answer:ntext',
			//'level',
			//'difficulty',
			//'hint:ntext',
			//'hint_cost',
			//'number',
			//'answer_type',

//			['class' => 'yii\grid\ActionColumn'],
		],
	]);?>
	
	<?php
	$this->registerJs("
        $('tbody td:not(.disable-click)').css('cursor', 'pointer');
        $('table tr td:not(.disable-click)').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if (e.target == this && id)
                location.href = '" . Url::to(['tasks/view']) . "?id=' + id;
        });
        ");
	?>
	
	<?php Pjax::end(); ?>

</div>
