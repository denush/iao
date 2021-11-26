<?php

include 'connect.php';
// ini_set("memory_limit", "512M");

$query = "SELECT * from czl_get_tmp_table()";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

// $data = 'hello from server again';

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);