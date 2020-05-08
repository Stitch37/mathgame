<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?=$form->field($model, 'number')->textInput()?>
    <p class="hint-block">Порядковый номер задачи</p>
	
	<?=$form->field($model, 'name')->textarea(['rows' => 1])?>
	
	<?=$form->field($model, 'text')->textarea(['rows' => 6])?>
	
	<?=$form->field($model, 'answer')->textarea(['rows' => 1])?>
    <p class="hint-block">Дробная часть числа отделеятся ТОЧКОЙ, а НЕ запятой (тип ответа в таком случае - число)</p>
	
	<?=$form->field($model, 'answer_type')->dropDownList(Yii::$app->params['answer_types'])?>
	
	<?=$form->field($model, 'difficulty')->textInput()?>
	
	<?=$form->field($model, 'reward')->textInput()?>
	
	<?=$form->field($model, 'level')->textInput()?>
    <p class="hint-block">ID уровня из его описания</p>
	
	<?=$form->field($model, 'hint')->textarea(['rows' => 6])?>
	
	<?=$form->field($model, 'hint_cost')->textInput()?>
    <p class="hint-block">Если цена равна нулю, то считается, что к этой задаче подсказки <b>нет</b></p>


    <div class="form-group">
		<?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
