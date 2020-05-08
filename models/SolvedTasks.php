<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solved_tasks".
 *
 * @property int  $id
 * @property int  $user Пользователь
 * @property int  $task Задача
 * @property int  $hint_used Использовалась ли подсказка
 * @property int  $timestamp Дата решения задачи
 * @property int  $tries Количество попыток решения задачи
 *
 * @property Task $task0
 */
class SolvedTasks extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'solved_tasks';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user', 'task', 'timestamp'], 'required'],
			[['user', 'task', 'hint_used', 'timestamp', 'tries'], 'default', 'value' => null],
			[['user', 'task', 'hint_used', 'timestamp', 'tries'], 'integer'],
			[['task'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task' => 'id']],
			[['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'user' => Yii::t('app', 'Пользователь'),
			'task' => Yii::t('app', 'Задача'),
			'hint_used' => Yii::t('app', 'Использовалась ли подсказка'),
			'timestamp' => Yii::t('app', 'Дата решения задачи'),
			'tries' => Yii::t('app', 'Количество попыток решения задачи'),
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTask0()
	{
		return $this->hasOne(Task::className(), ['id' => 'task']);
	}
}
