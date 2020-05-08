<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "levels".
 *
 * @property int         $id
 * @property string      $uid Уникальный идентификатор (Код уровня)
 * @property int         $difficulty Средний уровень сложности всех задач (От 1 до 10, где 10 - очень сложно)
 * @property string      $name Название
 * @property int         $created_at Дата создания
 * @property int         $private Приватный ли уровень (Доступ только по uid)
 * @property string      $lecture Лекция
 *
 * @property Lifehacks[] $lifehacks
 * @property Tasks[]     $tasks
 */
class Levels extends \yii\db\ActiveRecord
{
	public $difficulty;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'levels';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name', 'created_at'], 'required'],
			[['created_at', 'private'], 'default', 'value' => null],
			[['created_at', 'private'], 'integer'],
			[['name', 'lecture'], 'string'],
			[['uid'], 'string', 'max' => 8],
			[['uid'], 'unique'],
		];
	}
	
	public function afterFind()
	{
		parent::afterFind(); // TODO: Change the autogenerated stub
		$this->getDifficulty(); //Записать сложность уровня
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'uid' => Yii::t('app', 'Уникальный идентификатор (Код уровня)'),
			'name' => Yii::t('app', 'Название'),
			'created_at' => Yii::t('app', 'Дата создания'),
			'private' => Yii::t('app', 'Приватный ли уровень (Доступ только по uid)'),
			'lecture' => 'Лекция',
			'difficulty' => 'Общая сложность',
		];
	}
	
	public function getTasksCount()
	{
		return Tasks::find()
			->where(['level' => $this->id])
			->count();
	}
	
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLifehacks()
	{
		return $this->hasMany(Lifehacks::className(), ['level' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTasks()
	{
		return $this->hasMany(Tasks::className(), ['level' => 'id']);
	}
	
	public function isCompletedByCurrentUser()
	{
		$taskCount = Tasks::find()
			->where(['level' => $this->id])
			->count();
		
		if ($taskCount > 0)
		{
			$taskIds = ArrayHelper::map(Tasks::find()
				->where(['level' => $this->id])
				->select(['id'])
				->all(), 'id', 'id');
			$solvedCount = SolvedTasks::find()
				->where(['user' => Yii::$app->user->id])
				->andWhere(['in', 'task', $taskIds])
				->count();
			
			
			return $solvedCount == $taskCount;
		}
		return false;
	}
	
	public function getDifficulty()
	{
		if (!$this->difficulty)
		{
			$query = new Query();
			
			$row = $query->select('count(*) as c, sum(difficulty) as s')
				->where('level = ' . $this->id)
				->from('tasks')
				->all();
			
			if ($row)
			{
				$row = $row[0];
				if ($row['c'] != 0)
				{
					$this->difficulty = round($row['s'] / $row['c'], 1);
				} else
				{
					$this->difficulty = 0;
				}
				
			} else
			{
				$this->difficulty = 1;
			}
		}
		return $this->difficulty;
	}
}
