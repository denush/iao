<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	$filter = 'true';
	$show_all = 'false';

	if(isset($_POST['filter']) && $_POST['filter'])
		$filter = $_POST['filter'];

	if(isset($_POST['date_from']) && $_POST['date_from'])
		$date_from = $_POST['date_from'];
	else
		exit();

	if(isset($_POST['date_end']) && $_POST['date_end'])
		$date_end = $_POST['date_end'];
	else
		exit();

	if(isset($_POST['registry_type']) && $_POST['registry_type'])
		$registry_type = $_POST['registry_type'];
	else
		exit();

	if(isset($_POST['only_actual']) && $_POST['only_actual'])
		$show_all = $_POST['only_actual'] == 'false' ? 'true' : 'false';


	$query = "delete from data_registry where id in (select location_id from czl_get_registry('${date_from}', '${date_end}', ${registry_type}, ${show_all}) where ${filter})";
	//file_put_contents('log.log', $query);

	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

	// echo $query;

	//$arr = pg_fetch_assoc($result);

	//echo json_encode($arr, JSON_UNESCAPED_UNICODE);

	// Очистка результата
	echo 'ok';
	pg_free_result($result);
}
// Закрытие соединения

pg_close($dbconn);
?>