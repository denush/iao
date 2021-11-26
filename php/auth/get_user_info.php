<?php
	
include '../connect.php';

$user_id = $_POST['user_id'];

$query = "SELECT * from info_users where id='{$user_id}'";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$user_temp = pg_fetch_assoc($result);

$user_info = [
	'id' 				=> $user_temp['id'],
	'login' 		=> $user_temp['login'],
	'name' 			=> $user_temp['name']
];

echo json_encode($user_info, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);