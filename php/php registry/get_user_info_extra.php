<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	if(isset($_GET['id']))
		$user_id = $_GET['id'];
	else
		exit();
	
	session_write_close();

	$query = "SELECT * FROM amc_get_user_data_registry($user_id);";

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

	// echo $query;

	$arr = pg_fetch_assoc($result);

	echo json_encode($arr, JSON_UNESCAPED_UNICODE);

	// Очистка результата

	pg_free_result($result);
}
// Закрытие соединения

pg_close($dbconn);
?>