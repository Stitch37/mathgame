<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\SignupForm;
use Yii;
use yii\web\Response;

class SiteController extends BehaviorsController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}
	
	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}
	
	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}
		
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login())
		{
			return $this->goBack();
		}
		
		$model->password = '';
		return $this->render('login', [
			'model' => $model,
		]);
	}
	
	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();
		
		return $this->goHome();
	}
	
	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail']))
		{
			Yii::$app->session->setFlash('contactFormSubmitted');
			
			return $this->refresh();
		}
		return $this->render('contact', [
			'model' => $model,
		]);
	}
	
	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}
	
	
	public function actionReg()
	{
		$model = new RegisterForm();
		
		if ($model->load(Yii::$app->request->post()))
		{
			if ($user = $model->signup())
			{
				if (Yii::$app->getUser()->login($user))
				{
					return $this->goHome();
				}
			}
		}
		
		return $this->render('reg', [
			'model' => $model,
		]);
	}
}
