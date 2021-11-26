<?php

include 'connect.php';

$table_id = $_POST['table_id'];
$column = $_POST['column'];
// $filter = $_POST['filter'];
$filter = '';

$query = "SELECT * from czl_get_filter('{$table_id}', '{$column}', '{$filter}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);