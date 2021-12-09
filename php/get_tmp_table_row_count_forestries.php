<?php

include 'connect.php';

$table_id = $_POST['table_id'];
$filter = $_POST['filter'];

$query = "SELECT * from czl_get_tmp_table_row_count_forestries('{$table_id}', '{$filter}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);