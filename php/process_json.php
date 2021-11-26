<?php
	
include './connect.php';

$json_str = $_POST['json_str'];

// echo json_encode($json_str);
// exit;

$query = "SELECT * from czl_process_group_of_json('{$json_str}')";

$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
$result_code = pg_fetch_assoc($result);

echo json_encode($result_code, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);