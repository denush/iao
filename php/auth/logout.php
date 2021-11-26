<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    // cache for 1 day
}

//Если переменная auth из сессии не пуста и равна true, то...
session_start();
if (!empty($_SESSION['auth']) and $_SESSION['auth']) {

	session_destroy(); //разрушаем сессию для пользователя

	//Удаляем куки авторизации путем установления времени их жизни на текущий момент:
	setcookie('user_id', '', time(), '/'); //удаляем логин
	setcookie('key', '', time(), '/'); //удаляем ключ
}