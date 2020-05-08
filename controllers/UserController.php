<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BehaviorsController
{
	
	/**
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		if (User::isAdmin())
		{
			$searchModel = new UserSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Displays a single User model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		if (Yii::$app->user->id == $id || User::isAdmin())
		{
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if (User::isAdmin())
		{
			$model = new User();
			
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
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		if (Yii::$app->user->id == $id || User::isAdmin())
		{
			$model = $this->findModel($id);
			//TODO: БЕЗОПАСНОСТЬ!
//			if (User::isAdmin())
//			{
//				$model->load(Yii::$app->request->post());
//			} else
//			{
//				if (isset($_POST['User']))
//				{
//					$model->attributes = $_POST['FormName'];
//				}
//			}
			
			
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
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		if (User::isAdmin())
		{
			$this->findModel($id)->delete();
			
			return $this->redirect(['index']);
		}
		throw new ForbiddenHttpException('Нет доступа');
		
	}
	
	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null)
		{
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
