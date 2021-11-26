<?php
	include './connect.php';

	$old_pass = $_POST['old'];
	$new_pass = $_POST['new'];

	session_start();

	if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
		$user_id =  $_SESSION['registry_user_id'];
		session_write_close();

		$query = "SELECT * FROM czl_change_pass_registry(" . $user_id . ",'" . pg_escape_string($old_pass) . "','" . pg_escape_string($new_pass) . "')";
		$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
		$arr = pg_fetch_all($result);

		echo json_encode($arr, JSON_UNESCAPED_UNICODE);

	// Очистка результата

		pg_free_result($result);

	// Закрытие соединения

		pg_close($dbconn);
	}
	else {
		echo 'error';
	}
?>