<?php

namespace app\controllers;

use app\models\Levels;
use app\models\LevelsSearch;
use app\models\SolvedTasks;
use app\models\Tasks;
use app\models\TaskTries;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class GameController extends BehaviorsController
{
	
	public function actionView($id)
	{
		$model = Levels::findOne($id);
		if (!$model)
		{
			throw new NotFoundHttpException('Запрашиваемая страница не найдена');
		}
		
		return $this->render('view', ['model' => $model]);
	}
	
	
	public function actionIndex()
	{
		$searchModel = new LevelsSearch();
		
		$searchModel->private = 0;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionUpdate($id)
	{
		$model = Levels::findOne($id);
		if (!$model)
		{
			throw new NotFoundHttpException('Запрашиваемая страница не найдена');
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save())
		{
			Yii::$app->session->setFlash('success', 'Уровень ' . $model->name . ' был успешно изменен');
			return $this->redirect(['game/view', 'id' => $model->id]);
		}
		
		return $this->render('update',
			[
				'model' => $model,
			]);
	}
	
	public function actionCreate()
	{
		$model = new Levels();
		
		if ($model->load(Yii::$app->request->post()))
		{
			$model->created_at = time();
			if ($model->save())
			{
				Yii::$app->session->setFlash('success', 'Уровень ' . $model->name . ' был успешно создан');
				return $this->redirect(['game/index']);
			}
			
		}
		
		return $this->render('create',
			[
				'model' => $model,
			]);
	}
	
	
	public function actionPlay($id)
	{
		$level = Levels::findOne($id); //Найти в БД уровень по его ID
		if (!$level)
		{
			throw new NotFoundHttpException('Запрашиваемый уровень не найден.'); //Вывести ошибку, если уровень не найден
		}
		
		$tasksCount = $level->getTasksCount(); //Количество задач на данном уровне
		$taskNames = ArrayHelper::map(Tasks::find()->where(['level' => $level->id])->select(['name', 'number'])->all(), 'number', 'name'); //Массив, в котором ключии - это номера задач, а значения - это их названия
		
		
		return $this->render('play',
			[
				'tasksCount' => $tasksCount,
				'taskNames' => $taskNames,
				'levelName' => $level->name,
				'lecture' => $level->lecture,
			]); //Вызов метода, который с помощью представления play.php выведет пользователю задачи
	}
	
	//TODO: Игра в приватные уровни по UID
	public function actionPlayPrivate($uid)
	{
		throw new NotSupportedException('Игра по уникальному идентификатору не поддерживается');
	}
	
	
	public function actionGetTask($level, $number)
	{
		if (!Yii::$app->request->isAjax)
		{
			throw new NotSupportedException('Non-ajax requests are not supported');
		}
		
		$task = Tasks::findOne(['level' => $level, 'number' => $number]);
		if (!$task)
		{
			throw new NotFoundHttpException('Запрашиваемая задача не найдена.');
		}
		$out = [];
		$solvedTask = SolvedTasks::findOne(['user' => Yii::$app->user->id, 'task' => $task->id]);
		if (!$solvedTask)
		{
			//Создаем попытку решения задачи
			$newTry = new TaskTries();
			$newTry->task = $task->id;
			$newTry->user = Yii::$app->user->id;
			$newTry->successful = 0;
			$newTry->timestamp_start = time();
			
			if ($newTry->save())
			{
				$out = [
					'solved' => 0,
					'task_id' => $task->id,
					'name' => $task->name,
					'number' => $task->number,
					'text' => $task->text,
					'reward' => $task->reward,
					'time' => date('H:i:s'),
					'answer_type' => $task->answer_type,
				];
			}
		} else
		{
			$out =
				[
					'solved' => 1,
					'task_id' => $task->id,
					'name' => $task->name,
					'number' => $task->number,
					'text' => $task->text,
					'reward' => $task->reward,
					'tries' => $solvedTask->tries,
					'hint_used' => $solvedTask->hint_used,
					'time' => date('d.m.Y H:i:s', $solvedTask->timestamp),
				];
		}
		
		$resp = Yii::$app->getResponse();
		$resp->format = Response::FORMAT_JSON;
		$resp->data = $out;
		return $resp;
	}
	
	
	public function actionSolveTask($task_id, $answer)
	{
		if (!Yii::$app->request->isAjax)
		{
			throw new NotSupportedException('Non-post requests are not supported');
		}
		
		
		$task = Tasks::findOne($task_id);
		if (!$task)
		{
			throw new NotFoundHttpException('Запрашиваемая задача не найдена.');
		}
		
		$st = SolvedTasks::findOne(['task' => $task->id, 'user' => Yii::$app->user->id]);
		if ($st)
		{
			throw new BadRequestHttpException('Вы уже решили эту задачу.');
		}
		$try = TaskTries::find()
			->where(['task' => $task->id, 'user' => Yii::$app->user->id])
			->andWhere('timestamp_finish is null')
			->one();
		if (!$try)
		{
			throw new NotFoundHttpException('Вы не начали решение задачи, так что не можете её сдать.');
		}
		$try->timestamp_finish = time();
		$answer = mb_strtolower($answer);
		$try->answer = $answer;
		if (mb_strtolower($task->answer) === $answer)
		{
			$try->successful = 1;
		} else
		{
			$try->successful = 0;
		}
		if ($try->save())
		{
			$tries = TaskTries::find()
				->where(['user' => $try->user, 'successful' => 0, 'task' => $try->task])
				->count();
			if ($try->successful)
			{
				$tries++;
				$solvedTask = new SolvedTasks();
				$solvedTask->task = $try->task;
				$solvedTask->timestamp = $try->timestamp_finish;
				$solvedTask->user = $try->user;
				$solvedTask->tries = $tries;
				//Ищем в БД все попытки решения этой задачи с использованием подсказки
				$solvedTask->hint_used = (int)TaskTries::find()->where(['user' => Yii::$app->user->id, 'task' => $task->id, 'hint_used' => 1])->exists();
				
				$user = Yii::$app->user->getIdentity();
				if (!$solvedTask->hint_used)
				{
					$reward = $task->reward;
				} else
				{
					$reward = $task->reward - $task->hint_cost;
					if ($reward < 0)
					{
						$reward = 0;
					}
				}
				$user->money += $reward;
				$user->solved_tasks++;
				$user->save();
				
				if (!$solvedTask->save())
				{
				}
			} else
			{
				$newTry = new TaskTries();
				$newTry->user = $try->user;
				$newTry->task = $try->task;
				$newTry->timestamp_start = time();
				$newTry->successful = 0;
				$newTry->save();
			}
			
			
			$out =
				[
					'correct' => $try->successful,
					'time_consumed' => ($try->timestamp_finish - $try->timestamp_start) . ' секунд',
					'tries' => $tries,
				];
			
			$resp = Yii::$app->getResponse();
			$resp->format = Response::FORMAT_JSON;
			$resp->data = $out;
			return $resp;
		}
		throw new ServerErrorHttpException('Internal server error');
	}
	
	
	public function actionFinishSolvingTask($task_id)
	{
		$task = $this->findTask($task_id);
		$try = TaskTries::find()
			->where(['task' => $task->id, 'user' => Yii::$app->user->id])
			->andWhere('timestamp_finish is null')
			->one();
		if (!$try)
		{
			return 0;
		}
		$try->timestamp_finish = time();
		$try->successful = 0;
		return $try->save();
	}
	
	
	public function actionGetTip($task_id)
	{
		$task = $this->findTask($task_id);
		$try = TaskTries::find()
			->where(['task' => $task->id, 'user' => Yii::$app->user->id])
			->andWhere('timestamp_finish is null')
			->one();
		if (!$try)
		{
			throw new NotFoundHttpException('Вы не начали решать данную задачу.');
		}
		
		if ($task->hint_cost)
		{
			$try->hint_used = 1;
			
			if ($try->save())
			{
				return $task->hint;
			}
		}
		return 'Для данной задачи нет подсказки';
	}
	
	private function findTask($id)
	{
		$task = Tasks::findOne($id);
		if (!$task)
		{
			throw new NotFoundHttpException('Запрашиваемая задача не найдена.');
		}
		return $task;
	}
}
