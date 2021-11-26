<?php

include 'connect.php';

$user_id = $_POST['user_id'];

$query = "SELECT * from czl_get_tmp_tables_by_user({$user_id})";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);