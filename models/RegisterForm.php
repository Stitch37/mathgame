<?php

namespace app\models;

use yii\base\Model;

/**
 * Register form
 */
class RegisterForm extends Model
{
	
	public $username;
	public $email;
	public $password;
	public $surname;
	public $name;
	public $middlename;
	public $role;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'trim'],
			['username', 'required'],
			
			['username', 'string', 'min' => 5, 'max' => 20],
			[['surname', 'name', 'middlename'], 'string', 'max' => 80],
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 30],
			['password', 'required'],
			['password', 'string', 'min' => 6],
			['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Это имя пользователя занято.'],
			['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот эл. адрес уже используется.'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'username' => 'Имя пользователя',
			'email' => 'Электронная почта',
			'password' => 'Пароль',
			'surname' => 'Фамилия',
			'name' => 'Имя',
			'middlename' => 'Отчество',
		
		];
	}
	
	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 * @throws \yii\base\Exception
	 */
	public function signup()
	{
		
		if (!$this->validate())
		{
			return null;
		}
		
		$user = new User();
		$user->username = $this->username;
		$user->email = $this->email;
		$user->setPassword($this->password);
		
		$user->surname = $this->surname;
		$user->name = $this->name;
		$user->middlename = $this->middlename;
		$user->role = 10;
		
		
		$user->generateAuthKey();
		return $user->save() ? $user : null;
	}
	
}