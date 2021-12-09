<?php

ini_set("memory_limit", "999M");

include 'connect.php';

$final_result = [];

$user_id = $_POST['user_id'];
// $files_count = count($_FILES['files']['name']);

// echo json_encode($_FILES['file'], JSON_UNESCAPED_UNICODE);
// exit;

$tmp_name = $_FILES['file']['tmp_name'];
$file_name = $_FILES['file']['name'];

$content = file_get_contents($tmp_name);
if(mb_check_encoding($content,"WINDOWS-1251")) {
  $content = iconv('cp1251', 'utf-8', $content);
}
$content = trim($content);
$content = pg_escape_string($content);

$query = "SELECT * from czl_create_and_fill_tmp_table_forestries('{$file_name}', '{$user_id}', '{$content}')";
$result = pg_query($dbconn, $query);
$table_result = pg_fetch_array($result);

echo json_encode($table_result, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);