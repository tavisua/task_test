<?php

class AutorizationController {

	function index(){
		include_once WWW.'/autorization.html';
		return true;
	}
	function validate($username, $pass){
		$_SESSION['autorization'] = md5($username.$pass) == md5('admin123');
		if(!$_SESSION['autorization']){
			$message = 'Ошибка авторизации';
		}

		include_once WWW.'/autorization.html';
	}
}