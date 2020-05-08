<?php
/**
 * Project basic.
 * Author: Stitch37
 * Date: 06-Jun-19
 * Time: 12:51
 */

namespace app\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;

class BehaviorsController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'controllers' =>
							[
								'user', 'game', 'tasks', 'statistics',
							],
						'verbs' => ['GET', 'POST'],
						'roles' => ['@']
					],
					[
						'allow' => true,
						'controllers' =>
							[
								'user', 'game', 'tasks', 'statistics',
							],
						'actions' => ['delete'],
						'verbs' => ['POST'],
						'roles' => ['@']
					],
					[
						'allow' => true,
						'controllers' => ['site'],
						'actions' => ['login', 'reg'],
						'verbs' => ['GET', 'POST'],
						'roles' => ['?']
					],
					[
						'allow' => true,
						'controllers' => ['site'],
						'verbs' => ['GET', 'POST'],
						'roles' => ['?', '@']
					]
				]
			]
		];
	}
}