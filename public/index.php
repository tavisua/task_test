<?php

define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__).'/app');
define('DAYSIDE', dirname(__DIR__).'/dayside');



//Подключаю файлы системы
require_once ROOT.'/core/Router.php';
require_once APP.'/config/database.php';
//Вызываю роутер
$router = new Router();
$router->run();
