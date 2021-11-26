<?php

include '../connect.php';

$function_name = $_POST['function_name'];
$function_params = $_POST['function_params'];

$query = "SELECT * from {$function_name}({$function_params})";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$data = [];
while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);