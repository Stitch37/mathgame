<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property string  $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $surname
 * @property string  $name
 * @property string  $middlename
 * @property int     $solved_tasks Количество решенных задач
 */
class User extends ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}
	
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Имя пользователя',
			'status' => 'Статус',
			'role' => 'Роль',
			'surname' => 'Фамилия',
			'name' => 'Имя',
			'middlename' => 'Отчество',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Отпечаток пароля',
			'money' => 'Баланс',
			'email' => 'Электронная почта',
			'solved_tasks' => 'Кол-во решенных задач',
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			[['username', 'email'], 'required'],
			[['status', 'created_at', 'updated_at', 'role', 'solved_tasks'], 'integer'],
			['solved_tasks', 'default', 'value' => 0],
			[['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
			['username', 'string', 'max' => 40],
			[['surname', 'name', 'middlename'], 'string', 'max' => 40, 'min' => 2],
			[['username'], 'unique'],
			[['email'], 'unique'],
			[['password_hash', 'surname', 'name', 'middlename', 'username', 'email'], 'filter', 'filter' => 'trim']
		];
	}
	
	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert))
		{
			return false;
		}
		
		if ($insert)
		{
			$this->created_at = time();
		} else
		{
			$this->updated_at = time();
		}
		
		return true;
	}
	
	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}
	
	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}
	
	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}
	
	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}
	
	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}
	
	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 * @throws \yii\base\Exception
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}
	
	
	public function getFullName()
	{
		return trim($this->surname) . ' ' . trim($this->name) . ' ' . trim($this->middlename);
	}
	
	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}
	
	public static function isAdmin()
	{
		if (!Yii::$app->user->isGuest)
		{
			$user = static::findOne(['id' => Yii::$app->user->id, 'status' => self::STATUS_ACTIVE]);
			if ($user->role === 1)
			{
				return true;
			}
		}
		return false;
	}
}
