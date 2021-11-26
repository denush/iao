<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	$user_id = intval($_SESSION['registry_user_id']);
	$show_count = 50000;
	$start_num = 1;

	if(isset($_POST['start_num']))
		$start_num = $_POST['start_num'];

	if(isset($_POST['show_count']))
		$show_count = $_POST['show_count'];

	$query = "SELECT * FROM czl_get_som_full() limit ${show_count} offset ${start_num}-1";
	//file_put_contents('log.log', $query . "\r\n", FILE_APPEND);

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$arr = pg_fetch_all($result);

	$count_query = "SELECT count(*) FROM czl_get_som_full()";
	$count_result = pg_query($dbconn, $count_query) or die('Ошибка запроса: ' . pg_last_error());
	$count_arr = pg_fetch_all($count_result);
	
	if(!is_array($arr))
		$arr = array(null);

	array_push($arr, $count_arr[0]);

	echo json_encode($arr, JSON_UNESCAPED_UNICODE);

	// Очистка результата

	pg_free_result($result);
}
// Закрытие соединения

pg_close($dbconn);
?>