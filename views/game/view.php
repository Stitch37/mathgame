<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Levels */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Уровни', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="levels-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
		
    </p>
    <div class="container d-flex justify-content-between">
        <p>
	        <?=Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
	        <?=Html::a('Удалить', ['delete', 'id' => $model->id], [
		        'class' => 'btn btn-danger',
		        'data' => [
			        'confirm' => 'Вы действительно хотите удалить этот уровень?',
			        'method' => 'post',
		        ],
	        ])?>
        </p>
        <p><?=Html::a('Добавить задачи на этот уровень', ['tasks/create', 'level' => $model->id], ['class' => 'btn btn-primary'])?></p>
    </div>
	
	<?=DetailView::widget([
		'model' => $model,
		'options' => ['class' => 'table table-bordered detail-view table-dark'],
		'attributes' => [
			'id',
			'name:ntext',
			'lecture',
			'uid',
			'difficulty',
			[
				'attribute' => 'created_at',
				'value' => date('d.m.Y H:i:s', $model->created_at)
			],
			[
				'attribute' => 'private',
				'value' => $model->private ? 'Да' : 'Нет',
			],
		],
	])?>

</div>
