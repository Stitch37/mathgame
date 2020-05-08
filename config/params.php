<?php

return [
	'adminEmail' => 'ilin_ka@pspu.ru',
	'senderEmail' => 'noreply@example.com',
	'senderName' => 'Noreply',
	
	'answer_types' => [
		'int' => 'Число',
		'txt' => 'Текст',
	],
	
	'roles' => [
		1 => 'Администратор',
		2 => 'Преподаватель',
		10 => 'Пользователь',
	],
	
	'statuses' =>
		[
			0 => 'Удаленный',
			10 => 'Активный',
		],
	
	'maxTasks' => 18, //Максимальное число задач на 1 уровень
];
