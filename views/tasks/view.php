<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="tasks-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
		<?=Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-yellow'])?>
    </p>
	
	<?=DetailView::widget([
		'model' => $model,
		'options' => ['class' => 'table table-bordered detail-view table-dark'],
		'attributes' => [
			'id',
			'text:ntext',
			'name:ntext',
			'reward',
			'answer:ntext',
			'level',
			'difficulty',
			'hint:ntext',
			'hint_cost',
			'number',
			[
				'attribute' => 'answer_type',
				'value' => Yii::$app->params['answer_types'][$model->answer_type],
			],
		],
	])?>

</div>
