<?php

include './connect.php';

// Выполнение SQL запроса
session_start();

$user_id = intval($_SESSION['registry_user_id']);
$field = $_GET["field"];
$filter = $_GET["filter"];
$registry_type = $_GET["registry_type"];
$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];
$show_all = $_GET['show_only_actual'] == 'true' ? 'false' : 'true';

if ($filter != '') {
	$query = "SELECT DISTINCT($field) FROM czl_get_registry('${date_start}','${date_end}',$registry_type, $user_id, ${show_all}) where $filter order by $field";
}
else {
	$query = "SELECT DISTINCT($field) FROM czl_get_registry('${date_start}','${date_end}',$registry_type, $user_id, ${show_all}) order by $field";
}

//file_put_contents('filter.log', $query . "\r\n", FILE_APPEND);

$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

// echo $query;

$arr = pg_fetch_all($result);

echo json_encode($arr, JSON_UNESCAPED_UNICODE);

// Очистка результата

pg_free_result($result);

// Закрытие соединения

pg_close($dbconn);
?>