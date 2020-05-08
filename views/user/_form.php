<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?php
	if (!User::isAdmin())
	{
		?>
		<?=$form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true])?>
		
		<?=$form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true])?>
		<?php
	}
	?>
	<?=$form->field($model, 'surname')->textInput(['maxlength' => true])?>
	
	<?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
	
	<?=$form->field($model, 'middlename')->textInput(['maxlength' => true])?>
	
	<?php
	if (User::isAdmin())
	{
		?>
		<?=$form->field($model, 'username')->textInput(['maxlength' => true])?>
		
		<?=$form->field($model, 'email')->textInput(['maxlength' => true])?>
		
		<?=$form->field($model, 'status')->textInput()?>
        <p class="hint-block">10 - активный, 0 - удален</p>
		
		<?=$form->field($model, 'role')->textInput()?>
        <p class="hint-block">10 - пользователь, 1 - администратор, 2 - учитель</p>
		
		<?=$form->field($model, 'money')->textInput()?>
		<?=$form->field($model, 'solved_tasks')->textInput()?>
		<?php
	}
	?>


    <div class="form-group">
		<?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
