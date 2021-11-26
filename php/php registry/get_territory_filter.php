<?php

include './connect.php';

// Выполнение SQL запроса
session_start();

$user_id = intval($_SESSION['registry_user_id']);
$field = $_GET["field"];
$where = NULL;

if(isset($_GET['filter']) && strlen($_GET['filter']) && $_GET['filter'] != 'null')
	$where = ' where ' . $_GET['filter'];

$query = "SELECT DISTINCT($field) FROM amc_get_registry_location($user_id, 1, 50000, NULL)${where} order by $field";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

// echo $query;

$arr = pg_fetch_all($result);

echo json_encode($arr, JSON_UNESCAPED_UNICODE);

// Очистка результата

pg_free_result($result);

// Закрытие соединения

pg_close($dbconn);
?>