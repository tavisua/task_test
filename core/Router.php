<?php


class Router {
	private $routes = [];

	function __construct() {
		session_start();
		$routPath = APP.'/config/routes.php';
		$this->routes = include($routPath);
	}

	/*Получаю строку запроса
	 * @param string
	 * @return string*/
	private function getURI(){
		if($_SERVER['REQUEST_URI']){
			$uri = trim($_SERVER['REQUEST_URI'], '/');
		}
		return $uri;
	}

	function run(){

		$uri = $this->getURI();
		//Проверяю наличие запроса в config/routes.php
		foreach ($this->routes as $uriPannert=>$path) {
			if(preg_match("~$uriPannert~", $uri)) {
				$requestArray = explode('/', $path);
				$controllerName = ucfirst(array_shift($requestArray)); // Название контроллера
				$actionName = array_shift($requestArray); // Название action
				break;
			}
		}

		//Если не определен контроллер, устанавливаю по умолчанию отображение всех задач
		if(!$controllerName && !$actionName){
			$key = array_keys($this->routes)[0];
			$controllerName = ucfirst($key);
			$actionName = explode('/', $this->routes[$key])[1];
		}
		$controllerName .= 'Controller';

		//проверка на подключение к базе данных
		global $db;
		//Подключаем файл контроллера
		$controllerFile = APP.'/controllers/'.$controllerName.'.php';
		//Шапка страницы
		include_once WWW.'/header.html';
		if(file_exists($controllerFile)) {
			include_once $controllerFile;
		}else{
			$error = 'Не найден файл контроллера';
			include_once WWW.'/404.html';
		}
		if (!$db){//Если сбой подключения к базе данных
			$error = 'Сбой подключения к базе данных';
			include_once WWW.'/404.html';
		}
		//Футер страницы
		include_once WWW.'/footer.html';
		if ($error)
			exit();

		//Определяю действие и ИД
		$requestArray = explode('/', key($_REQUEST));
		array_shift($_REQUEST);

		//Подгружаю дополнительные параметры из $_REQUEST
		foreach ($_REQUEST as $key=>$value){
			$requestArray[$key]=$value;
		}


		foreach ($requestArray as $key=>$value){
			$parameters[$key] = $value;
		}

		if(count($parameters)>1)
			array_shift($parameters);
		elseif(!$parameters)
			$parameters = [];


		//Если выполняется закрытие сессии администратора
		if(strval(array_keys($parameters)[0]) == 'exit'){
			$parameters = [];
		}

		//Создаю объект контроллера и вызываю метод запроса
		$controller = new $controllerName();
		if($_FILES){
			$parameters['picture'] = $_FILES['picture'] ["tmp_name"];
		}

		$result = call_user_func_array(array($controller, $actionName), $parameters);
	}
}