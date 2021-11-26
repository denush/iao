<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	session_write_close();
	
	$query = "SELECT id, name FROM info_users_registry where id <> 1 order by name";

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

	// echo $query;

	$arr = pg_fetch_all($result);

	echo json_encode($arr, JSON_UNESCAPED_UNICODE);

	// Очистка результата

	pg_free_result($result);
}
// Закрытие соединения

pg_close($dbconn);
?>