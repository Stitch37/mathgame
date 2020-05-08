<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
		<?=Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success'])?>
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
			'surname:ntext',
			'name:ntext',
			'middlename:ntext',
			'username:ntext',
			'email:ntext',
			'status',
			//'auth_key:ntext',
			//'password_reset_token:ntext',
			//'created_at',
			//'updated_at',
			
			//'role',
			//'score',
			'money',
			'solved_tasks',
		],
	]);?>
	
	
	<?php
	$this->registerJs("
        $('tbody td:not(.disable-click)').css('cursor', 'pointer');
        $('table tr td:not(.disable-click)').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if (e.target == this && id)
                location.href = '" . Url::to(['user/view']) . "?id=' + id;
        });
        ");
	?>
	
	<?php Pjax::end(); ?>

</div>
