<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация учетной записи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?=Html::encode($this->title)?></h1>
    <p>Пожалуйста, заполните следующие поля для регистрации:</p>
    <div class="row">
        <div class="col-lg-8">
			
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
			<?=$form->field($model, 'surname')->textInput(['maxlength' => true, 'autofocus' => true])?>
			<?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
			<?=$form->field($model, 'middlename')->textInput(['maxlength' => true])?>
			<?=$form->field($model, 'username')->textInput(['maxlength' => true])?>
			<?=$form->field($model, 'email')->textInput(['maxlength' => true])?>
			<?=$form->field($model, 'password')->passwordInput(['maxlength' => true])?>
            <p class="hint-block">Пароль должен быть не менее 6 символов</p>

            <p style="color: red">Нажимая кнопку "Зарегистрироваться" вы даете согласие на обработку персональных
                данных.</p>
            <div class="form-group">
				<?=Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
            </div>
			<?php ActiveForm::end(); ?>

        </div>
    </div>
</div>