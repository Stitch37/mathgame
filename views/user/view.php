<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
if (app\models\User::isAdmin())
{
	$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
		<?=Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
    </p>
	
	<?=DetailView::widget([
		'model' => $model,
		'options' => ['class' => 'table table-bordered detail-view table-dark'],
		'attributes' => [
			'id',
			'username:ntext',
			'email:ntext',
			'surname:ntext',
			'name:ntext',
			'middlename:ntext',
			[
				'attribute' => 'role',
				'value' => Yii::$app->params['roles'][$model->role],
			],
			[
				'attribute' => 'status',
				'value' => Yii::$app->params['statuses'][$model->status],
			],
			'money',
			'solved_tasks',
			[
				'label' => 'Дата регистрации',
				'value' => date('d.m.Y', $model->created_at),
			],
		],
	])?>

</div>
