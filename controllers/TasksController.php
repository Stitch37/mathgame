<?php

namespace app\controllers;

use app\models\Tasks;
use app\models\TasksSearch;
use app\models\User;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends BehaviorsController
{
	
	/**
	 * Lists all Tasks models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		if (User::isAdmin())
		{
			$searchModel = new TasksSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Displays a single Tasks model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		if (User::isAdmin())
		{
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Creates a new Tasks model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if (User::isAdmin())
		{
			$model = new Tasks();
			
			if ($level = Yii::$app->request->get('level'))
			{
				$model->level = $level;
				
				$number = Tasks::find()
					->where(['level' => $model->level])
					->select('number')
					->orderBy(['number' => SORT_DESC])
					->one();
				if (!$number)
				{
					$number = 0;
				} else
				{
					$number = $number['number'];
				}
				$number++;
				$model->number = $number;
			}
			
			if ($model->load(Yii::$app->request->post()) && $model->save())
			{
				return $this->redirect(['view', 'id' => $model->id]);
			}
			
			return $this->render('create', [
				'model' => $model,
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Updates an existing Tasks model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		if (User::isAdmin())
		{
			$model = $this->findModel($id);
			
			if ($model->load(Yii::$app->request->post()) && $model->save())
			{
				return $this->redirect(['view', 'id' => $model->id]);
			}
			
			return $this->render('update', [
				'model' => $model,
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
	}
	
	/**
	 * Finds the Tasks model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Tasks the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Tasks::findOne($id)) !== null)
		{
			return $model;
		}
		
		throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
	}
}
