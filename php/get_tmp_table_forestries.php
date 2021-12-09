<?php

include 'connect.php';

$table_id = $_POST['table_id'];
$current_page = $_POST['current_page'];
$per_page = $_POST['per_page'];
// $filter = '';
$filter = $_POST['filter'];

$start_num = $current_page * $per_page - $per_page + 1;

$query = "SELECT * from czl_get_tmp_table_forestries('{$table_id}', '{$start_num}', '{$per_page}', '{$filter}')";
// $query = "SELECT * from czl_get_tmp_table('{$table_id}', '1', '100', '(id=''213'') and (is_row_correct=''false'')')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);