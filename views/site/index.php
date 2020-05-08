<?php

/* @var $this yii\web\View */

$this->title = 'Храм задач';

use app\models\User;
use yii\helpers\Html;

?>
<div class="site-index">

    <section class="site-title jumbotron d-flex align-items-center container-fluid">
        <div class="container">
            <h1><?=Yii::$app->name?></h1>

            <p class="lead">«Если вы хотите научиться плавать, то смело входите в воду, а если хотите научиться решать
                задачи, то решайте их!» (Д. Пойа)</p>


            <p>
				<?=Html::a('Приступить к игре', ['game/index'], ['class' => 'btn btn-lg btn-success'])?>
            </p>
        </div>
    </section>

    <section class="block-motivation jumbotron">

        <p>Решай задачи и получай за это баллы,</p>
        <p>Которые ты сможешь менять на подсказки.</p>
        <p>Но не торопись потратить все баллы, у нас есть рейтинг среди пользователей.</p>
        <p>Соревнуйся, решай задачи и тем самым ты подготовишься к экзаменам.</p>
        <p>А, и еще, чуть не забыли, заходи к нам почаще,</p>
        <p>уровней станет больше, а задачи всё интереснее!</p>
        <p>Ждем тебя в Храме Задач. Успехов тебе, друг :3</p>

    </section>

    <section class="rate">
        <div class="container">
            <h2>Рейтинг игроков</h2>
            <table class="table table-bordered table-dark table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Имя</th>
                    <th>Количество решенных задач</th>
                </tr>
                </thead>
				<?php
				$users = User::find()
					->where(['status' => User::STATUS_ACTIVE])
					->andWhere(['>', 'solved_tasks', 0])
					->orderBy(['solved_tasks' => SORT_DESC])
					->limit(10)
					->all();
				
				$i = 1;
				foreach ($users as $u)
				{
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>' . $u->getFullName() . '</td>';
					echo '<td>' . $u->solved_tasks . '</td>';
					echo '</tr>';
					$i++;
				}
				?>
            </table>
        </div>
    </section>
</div>

<script>
	document.querySelector('#main-container').classList.remove('container');
</script>
