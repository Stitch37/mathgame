<?php

use yii\helpers\Html;
use yii\redactor\widgets\Redactor;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Levels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="levels-form">
	
	<?php $form = ActiveForm::begin(); ?>
	<?php
	if (!$model->uid)
	{
		$model->uid = Yii::$app->security->generateRandomString(8);
	}
	?>
	<?=$form->field($model, 'uid')->textInput(['maxlength' => true])?>
	
	<?=$form->field($model, 'name')->textarea(['rows' => 1])?>
	
	<?php //$form->field($model, 'lecture')->textarea(['rows' => 6])?>
	<?=$form->field($model, 'lecture')->widget(Redactor::className(), [
		'clientOptions' => [
			'lang' => 'ru',
		]
	])?>
	
	
	<?=$form->field($model, 'private')->checkbox()?>

    <div class="form-group">
		<?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
