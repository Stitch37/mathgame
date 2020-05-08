<?php
/**
 * Project OPD.
 * Author: Stitch37
 * Date: 10-Jun-19
 * Time: 18:26
 */


$this->title = 'Уровень: ' . $levelName;

use app\models\User;
use yii\helpers\Html;

?>
<div class="site-index">
	<div class="container">

		<h1 class="mb-5 text-center"><?= $levelName ?></h1>

      <?php
      if (User::isAdmin()) {
          ?>

				<p class="text-right"><?= Html::a('Об уровне', ['game/view', 'id' => $_GET['id']], ['class' => 'btn btn-yellow']) ?></p>

          <?php
      }
      ?>

		<div class="mb-5">
			<details>
				<summary class="mb-2">Лекция</summary>
          <?= $lecture ?: '<p>К данному уровню нет лекции</p>' ?>
			</details>
		</div>


		<div class="row levels justify-content-around">
        <?php
        for ($i = 1; $i <= $tasksCount; $i++) {
            //По 4 задачи в ряду
            if ($i % 4 === 0) {
                echo '</div>';
                echo '<div class="row levels justify-content-around">';
            }
            echo '<div data-id="' . $i . '">(' . $i . ')' . $taskNames[$i] . '</div>';
        }
        ?>
		</div>

	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="task" tabindex="-1" role="dialog" aria-labelledby="taskTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="taskTitle">Задача <?= $levelName ?> уровня</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="hint"></p>
				<div class="content"></div>
				<input type="text" id="answer">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info btnHint">Подсказка</button>
				<!--				<button type="button" class="btn btn-secondary btnClose" data-dismiss="modal">Закрыть</button>-->
				<button type="button" class="btn btn-success btnCheck">Проверить</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.querySelectorAll('.levels div').forEach(el => el.onclick = (event) =>
	{
		$.ajax('<?= yii\helpers\Url::to(["game/get-task"]) ?>', {
			method: 'GET',
			data: {
				level: <?= Yii::$app->request->get('id') ?>,
				number: event.target.dataset.id,
			},
			dataType: 'JSON',
		})
		.done((task) =>
		{
			let {task_id, name, number, text, reward, time, solved} = task;
			document.querySelector('#hint').textContent = '';
			document.querySelector('#task').dataset.id = task_id;
			document.querySelector('#task').dataset.time_start = time;
			document.querySelector('#task .modal-body .content').innerHTML = `<p>${text}</p>`;
			document.querySelector('#taskTitle').innerHTML = number + ') ' + name + ' (Награда: ' + reward + ')' + '<br>' + (solved ? 'Решена: ' : 'Начало решения: ') + time;
			if (solved)
			{
				let {tries, hint_used} = task;
				document.querySelector('#task .btnHint').setAttribute('disabled', 'disabled');
				document.querySelector('#taskTitle').innerHTML += `<br>Кол-во попыток: ${tries}, Кол-во подсказок: ${hint_used}`;
				document.querySelector('#answer').setAttribute('disabled', 'disabled');
				document.querySelector('#task .btnCheck').setAttribute('disabled', 'disabled');
			} else
			{
				let {answer_type} = task;
				document.querySelector('#task .btnHint').removeAttribute('disabled');
				document.querySelector('#answer').removeAttribute('disabled');
				document.querySelector('#answer').type = (answer_type === 'int' ? 'number' : 'text');
				document.querySelector('#task .btnCheck').removeAttribute('disabled');

				document.querySelector('#task .btnHint').onclick = () =>
				{
					$.ajax('<?= yii\helpers\Url::to(["game/get-tip"]) ?>', {
						method: 'GET',
						data: {
							task_id: task_id,
						},
						success: (hint) =>
						{
							document.querySelector('#hint').innerHTML = `Подсказка: ${hint}`;
						},
						error: (error) =>
						{
							alert('Произошла техническая ошибка');
							console.error(error);
						},
					})
					;
				};

				window.onbeforeunload = (event) =>
				{
					$.ajax('<?= yii\helpers\Url::to(["game/finish-solving-task"]) ?>', {
						method: 'GET',
						data: {
							task_id: task_id,
						},
					});
				};
			}

			document.querySelector('#answer').value = '';
			$('#task').modal({
				backdrop: 'static',
				keyboard: false,
			});
		})
		.fail(() => console.error('Не удалось получить задачу'));
	});
	document.querySelector('#task .close').onclick = () =>
	{
		$.ajax('<?= yii\helpers\Url::to(["game/finish-solving-task"]) ?>', {
			method: 'GET',
			data: {
				task_id: document.querySelector('#task').dataset.id,
			},
		});
	};
	document.querySelector('#task .btnCheck').onclick = () =>
	{
		window.onbeforeunload = undefined;
		$.ajax('<?= yii\helpers\Url::to(["game/solve-task"]) ?>', {
				method: 'GET',
				data: {
					task_id: document.querySelector('#task').dataset.id,
					answer: document.querySelector('#answer').value,
				},
				dataType: 'JSON',
			},
		)
		.done((res) =>
		{
			let {correct, time_consumed} = res;
			if (correct)
			{
				$('#task').modal('hide');
			}
			alert('Выполнено ' + (correct ? 'правильно' : 'неправильно') + ' за ' + time_consumed);
		})
		.fail((error) =>
		{
			alert('Произошла техническая ошибка');
			console.error(error);
		});
	};
</script>
