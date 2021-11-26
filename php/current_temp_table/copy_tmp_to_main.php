<?php

include '../connect.php';

$table_id = $_POST['table_id'];

$query = "SELECT * from czl_copy_tmp_to_main('{$table_id}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);