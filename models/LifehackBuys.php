<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lifehack_buys".
 *
 * @property int       $id
 * @property int       $user
 * @property int       $lifehack
 * @property int       $timestamp
 *
 * @property Lifehacks $lifehack0
 */
class LifehackBuys extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'lifehack_buys';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user', 'lifehack', 'timestamp'], 'required'],
			[['user', 'lifehack', 'timestamp'], 'default', 'value' => null],
			[['user', 'lifehack', 'timestamp'], 'integer'],
			[['lifehack'], 'exist', 'skipOnError' => true, 'targetClass' => Lifehacks::className(), 'targetAttribute' => ['lifehack' => 'id']],
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
			'user' => Yii::t('app', 'User'),
			'lifehack' => Yii::t('app', 'Lifehacks'),
			'timestamp' => Yii::t('app', 'Timestamp'),
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLifehack0()
	{
		return $this->hasOne(Lifehacks::className(), ['id' => 'lifehack']);
	}
}
