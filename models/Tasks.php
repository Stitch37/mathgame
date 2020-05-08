<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int           $id
 * @property string        $text Текст задачи
 * @property string        $name Название
 * @property int           $reward Награда за выполнение
 * @property string        $answer Правильный ответ
 * @property int           $level Уровень
 * @property int           $difficulty Уровень сложности
 * @property string        $hint Подсказка к задаче
 * @property int           $hint_cost Цена подсказки
 * @property int           $number Номер задачи в рамках уровня
 * @property string        $answer_type Тип ответа (int, txt)
 *
 * @property SolvedTasks[] $solvedTasks
 * @property TaskTries[]   $taskTries
 * @property Levels        $level0
 */
class Tasks extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'tasks';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['text', 'name', 'answer', 'level', 'difficulty', 'number'], 'required'],
			[['text', 'name', 'answer', 'hint', 'answer_type'], 'string'],
			[['reward', 'level', 'difficulty', 'hint_cost', 'number'], 'default', 'value' => null],
			['answer_type', 'default', 'value' => 'int'],
			[['reward', 'level', 'difficulty', 'hint_cost', 'number'], 'integer'],
			[['level'], 'exist', 'skipOnError' => true, 'targetClass' => Levels::className(), 'targetAttribute' => ['level' => 'id']],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'text' => Yii::t('app', 'Текст задачи'),
			'name' => Yii::t('app', 'Название'),
			'reward' => Yii::t('app', 'Награда за выполнение'),
			'answer' => Yii::t('app', 'Правильный ответ'),
			'level' => Yii::t('app', 'Уровень'),
			'difficulty' => Yii::t('app', 'Уровень сложности'),
			'hint' => Yii::t('app', 'Подсказка к задаче'),
			'hint_cost' => Yii::t('app', 'Цена подсказки'),
			'number' => Yii::t('app', 'Номер задачи в рамках уровня'),
			'answer_type' => 'Тип ответа',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSolvedTasks()
	{
		return $this->hasMany(SolvedTasks::className(), ['task' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTaskTries()
	{
		return $this->hasMany(TaskTries::className(), ['task' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel0()
	{
		return $this->hasOne(Levels::className(), ['id' => 'level']);
	}
}
