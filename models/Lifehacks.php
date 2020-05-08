<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lifehacks".
 *
 * @property int            $id
 * @property string         $name Название
 * @property int            $level Уровень
 * @property string         $text Текст
 * @property string         $example Пример задачи с решением
 * @property int            $price Цена за получение
 * @property string         $tasks Номера задач, которые может помочь решить на этом уровне
 *
 * @property LifehackBuys[] $lifehackBuys
 * @property Level          $level0
 */
class Lifehacks extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'lifehacks';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name', 'text', 'example', 'price', 'tasks'], 'required'],
			[['name', 'text', 'example', 'tasks'], 'string'],
			[['level', 'price'], 'default', 'value' => null],
			[['level', 'price'], 'integer'],
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
			'name' => Yii::t('app', 'Название'),
			'level' => Yii::t('app', 'Уровень'),
			'text' => Yii::t('app', 'Текст'),
			'example' => Yii::t('app', 'Пример задачи с решением'),
			'price' => Yii::t('app', 'Цена за получение'),
			'tasks' => Yii::t('app', 'Номера задач, которые может помочь решить на этом уровне'),
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLifehackBuys()
	{
		return $this->hasMany(LifehackBuys::className(), ['lifehack' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel0()
	{
		return $this->hasOne(Level::className(), ['id' => 'level']);
	}
}
