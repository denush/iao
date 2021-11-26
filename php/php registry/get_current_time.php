<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	date_default_timezone_set('Europe/Moscow');
	$time = date('H:i');
	echo json_encode(array('time' => $time), JSON_UNESCAPED_UNICODE);
}
// Закрытие соединения

pg_close($dbconn);
?>