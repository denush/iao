<?php

include '../connect.php';

// $fo_id = $_POST['fo_id'];
$fo_id = '5'; // сибирский федеральный округ

$query = "SELECT * from czl_get_all_regions('{$fo_id}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);