<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth'] && isset($_GET['registry_type']) && $_GET['registry_type']) {
	
	$registry_type = intval($_GET['registry_type']);
	$user_id = intval($_SESSION['registry_user_id']);
	
	$query = "SELECT * FROM amc_get_registry_regions(${registry_type}, ${user_id});";

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