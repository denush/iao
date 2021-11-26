<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	$user_id = intval($_SESSION['registry_user_id']);
	$registry_type = NULL;
	$date = NULL;
	$date_end = NULL;
	$where = NULL;
	$show_count = 0;
	$start_num = 1;
	$show_all = 'false';

	if(isset($_POST['start_num']))
		$start_num = $_POST['start_num'];

	if(isset($_POST['show_count']))
		$show_count = $_POST['show_count'];

	if(isset($_POST['show_only_actual']))
		$show_all = $_POST['show_only_actual'] == 'true' ? 'false' : 'true';

	if(isset($_POST['date']))
	{
		$date = $_POST['date'];
		$date_end = $date;
	} else 
		exit();

	if(isset($_POST['date_end']))
		$date_end = $_POST['date_end'];

	if(isset($_POST['registry_type']))
		$registry_type = $_POST['registry_type'];

	if(isset($_POST['where']) && strlen($_POST['where']))
		$where = ' where ' . $_POST['where'];

	$query = "SELECT * FROM czl_get_registry('{$date}','{$date_end}',${registry_type}, ${user_id}, ${show_all})${where} limit ${show_count} offset ${start_num}-1";
	//file_put_contents('log.log', $query . "\r\n", FILE_APPEND);

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$arr = pg_fetch_all($result);

	$count_query = "SELECT count(*) FROM czl_get_registry('{$date}','{$date_end}',$registry_type, ${user_id}, ${show_all})${where}";
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