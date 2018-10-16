<?php

class AutorizationController {
	//Отображение формы авторизации
	function index(){
		include_once WWW . '/autorization/autorization.html';
		return true;
	}
	//Проверка авторизации пользователя
	function validate($username, $pass){
		$_SESSION['autorization'] = md5($username.$pass) == md5('admin123');
		if(!$_SESSION['autorization']){
			$message = 'Ошибка авторизации';
		}

		include_once WWW . '/autorization/autorization.html';
	}
	/*Завершение сессии
	 * */
	function exitAdmin(){
		session_destroy();
		require_once 'TasksController.php';
		unset($_SESSION['autorization']);
		$Tasks = new TasksController();
		$Tasks->showTasks();
	}
}