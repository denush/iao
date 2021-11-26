<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	$user_id = intval($_SESSION['registry_user_id']);
	$where = NULL;

	if(isset($_GET['where']) && strlen($_GET['where']) && $_GET['where'] != 'null')
		$where = 'where '. $_GET['where'];

	$query = "SELECT * FROM amc_get_registry_location(${user_id}, 1, 50000, NULL)${where}";
	//file_put_contents('log.log', $query . "\r\n", FILE_APPEND);

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$arr = pg_fetch_all($result);

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename=Справочник_территорий.csv');

	$output = fopen("php://output", "r+");

	foreach ($arr as $key => $value) {
		$line = array();
		array_push($line, iconv('UTF-8', 'Windows-1251//TRANSLIT', $value['region_name']));
		array_push($line, iconv('UTF-8', 'Windows-1251//TRANSLIT', $value['forestry_name']));
		array_push($line, iconv('UTF-8', 'Windows-1251//TRANSLIT', $value['localforestry_name']));
		array_push($line, iconv('UTF-8', 'Windows-1251//TRANSLIT', $value['subforestry_name']));
		fputcsv($output, $line, ";");
	}

	// Очистка результата
	pg_free_result($result);
}
// Закрытие соединения

pg_close($dbconn);
?>