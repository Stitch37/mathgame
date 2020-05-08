<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_tries".
 *
 * @property int    $id
 * @property int    $task Задача
 * @property int    $user Пользователь
 * @property int    $timestamp_start Время начала решения
 * @property int    $timestamp_finish Время окончания решения
 * @property int    $successful Правильно ли решена задача
 * @property int    $hint_used Использовалась ли подсказка
 * @property string $answer Ответ
 *
 * @property Task   $task0
 */
class TaskTries extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'task_tries';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['task', 'user', 'timestamp_start'], 'required'],
			[['task', 'user', 'timestamp_start', 'timestamp_finish', 'successful', 'answer'], 'default', 'value' => null],
			[['task', 'user', 'timestamp_start', 'timestamp_finish', 'successful', 'hint_used'], 'integer'],
			['answer', 'string'],
			['hint_used', 'default', 'value' => 0],
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
			'task' => Yii::t('app', 'Задача'),
			'user' => Yii::t('app', 'Пользователь'),
			'timestamp_start' => Yii::t('app', 'Время начала решения'),
			'timestamp_finish' => Yii::t('app', 'Время окончания решения'),
			'successful' => Yii::t('app', 'Правильно ли решена задача'),
			'hint_used' => 'Использовалась ли подсказка',
			'answer' => 'Ответ пользователя',
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
