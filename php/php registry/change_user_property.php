<?php
include './connect.php';

// Выполнение SQL запроса
session_start();

if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
	$user_id = $_POST['user_id'];
	$user_fio = $_POST['user_fio'];
	$user_login = $_POST['user_login'];
	//$user_password = $_POST['user_password'];
	$user_is_admin = $_POST['user_is_admin'];
	$user_is_locked = $_POST['user_is_locked'];
	$regions_view = $_POST['regions_view'];
	$regions_change = $_POST['regions_change'];
	$regions_delete = $_POST['regions_delete'];
	$regions_extract = $_POST['regions_extract'];
	//
	$location = $_POST['location'] ? "'" . $_POST['location'] . "'" : 'null';
	$decree = $_POST['decree'] ? "'" . $_POST['decree'] . "'" : 'null';
	$role_decree = $_POST['role_decree'] ? "'" . $_POST['role_decree'] . "'" : 'null';

	$query = "SELECT * FROM amc_change_user_data_registry($user_id, '${user_login}', '${user_fio}', ${user_is_admin}, '${regions_view}', '${regions_change}', '${regions_delete}', '${regions_extract}', ${location}, ${decree}, ${role_decree}, ${user_is_locked})";
	//file_put_contents('log.log', $query . "\r\n", FILE_APPEND);
	//echo $query;


	if (!pg_connection_busy($dbconn)) {
	    pg_send_query($dbconn, $query);
	}  
	$result = pg_get_result($dbconn);

	$error_code = 0;
	$error_text = NULL;

	$error_code = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
	if($error_code == 23505)
	{
		$error_text = 'Пользователь с данным логином уже зарегистрирован в системе. Пожалуйста, используйте другой логин.';
	}
	
	$response_array = array('code' => $error_code, 'text' => $error_text);
	echo json_encode($response_array, JSON_UNESCAPED_UNICODE);

	pg_free_result($result); 
}
// Закрытие соединения

pg_close($dbconn);
?>