<?php

ini_set("memory_limit", "999M");

include 'connect.php';

$final_result = [];

$user_id = $_POST['user_id'];

$tmp_name = $_FILES['file']['tmp_name'];
$file_name = $_FILES['file']['name'];

$content = file_get_contents($tmp_name);
if(mb_check_encoding($content,"WINDOWS-1251")) {
  $content = iconv('cp1251', 'utf-8', $content);
}
$content = trim($content);
$content = pg_escape_string($content);

$query = "SELECT * from czl_create_and_fill_tmp_table('{$file_name}', '{$user_id}', '{$content}')";
$result = pg_query($dbconn, $query);
$table_result = pg_fetch_array($result);

echo json_encode($table_result, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);


exit;








ini_set("memory_limit", "999M");


include 'connect.php';

// echo json_encode($_FILES['files']);
// exit;

// if(!(isset($_FILES) && $_FILES['files']['error'] == 0)) { // Проверяем, загрузил ли пользователь файл
// 	$result = [
// 		"code" => 2,
// 		"text" => 'Произошла ошибка при загрузке файла'
// 	];

// 	echo json_encode($result, JSON_UNESCAPED_UNICODE);
// 	pg_close($dbconn);
// 	exit;
// }

// echo json_encode($_FILES['files']);
// exit;

$final_result = [];

// $query = "SELECT * from czl_create_tmp_table()";
// $result = pg_query($dbconn, $query);
// $table_id = pg_fetch_array($result)[0];

$user_id = $_POST['user_id'];
$files_count = count($_FILES['files']['name']);
// $files_content = [];

for ($i = 0; $i < $files_count; ++$i) {

  // $query = "SELECT * from czl_create_tmp_table()";
  // $result = pg_query($dbconn, $query);
  // $table_id = pg_fetch_array($result)[0];

  $tmp_name = $_FILES['files']['tmp_name'][$i];
  $file_name = $_FILES['files']['name'][$i];

  $content = file_get_contents($tmp_name);
  if(mb_check_encoding($content,"WINDOWS-1251")) {
    $content = iconv('cp1251', 'utf-8', $content);
  }
  $content = trim($content);
  $content = pg_escape_string($content);

  // $query = "SELECT * from czl_fill_tmp_table('{$content}', '{$table_id}')";
  // $result = pg_query($dbconn, $query);
  // $table_result = pg_fetch_array($result);




  $query = "SELECT * from czl_create_and_fill_tmp_table('{$file_name}', '{$user_id}', '{$content}')";
  $result = pg_query($dbconn, $query);
  $table_result = pg_fetch_array($result);



  $final_result[] = $table_result;
}

// $file_name = $_FILES['file']['tmp_name'];

// $content = file_get_contents($file_name);
// if(mb_check_encoding($content,"WINDOWS-1251")) {
//   $content = iconv('cp1251', 'utf-8', $content);
// }
// $content = trim($content);

// // echo json_encode($content, JSON_UNESCAPED_UNICODE);

// if ($table_id) {
//   $query = "SELECT * from czl_fill_tmp_table('{$content}', '{$table_id}')";
//   $result = pg_query($dbconn, $query);
//   $table_result = pg_fetch_array($result);

//   echo json_encode($table_result, JSON_UNESCAPED_UNICODE);
// }

echo json_encode($final_result, JSON_UNESCAPED_UNICODE);
pg_close($dbconn);