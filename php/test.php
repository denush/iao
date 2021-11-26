<?php

include 'connect.php';

// $query = "SELECT * from test_table";
// $result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

// $data = [];
// while ($row = pg_fetch_assoc($result)) {
// 	$data[] = $row;
// }

$data = 'test';

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);