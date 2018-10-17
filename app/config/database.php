<?php

global $db;

//Параметры для localhost
//$host = 'localhost';
//$dbname = 'task-book';
//$user_name = 'root';
//$pass = '';
//Параметры для сервера
$host = 'localhost';
$dbname = 'tavis_task';
$user_name = 'tavis';
$pass = 'Vhry8bfs';

$db = @mysqli_connect($host, $user_name, $pass, $dbname);


