<?php
	const NO_ERROR = 0;
	const ERROR_CSV = 1;
	const ERROR_SQL = 2;
	const ERROR_FILE = 3;

	set_time_limit(0); //no limit
	ini_set('upload_max_filesize', '40M');
	ini_set('post_max_size', '40M');
	ini_set('memory_limit', '256M');

	function isUTF8($string) {
	    return (utf8_encode(utf8_decode($string)) == $string);
	}

	include './connect.php';

	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}
	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	}

	session_start();

	if(!isset($_SESSION['registry_user_id']))
		exit();
	else
		$user_id = $_SESSION['registry_user_id'];

	if(empty($_FILES))
		exit();

	$region_id = NULL;
	$registry_type = NULL;
	$create_date = NULL;
	$filename = NULL;

	if(isset($_POST['region_id']))
		$region_id = $_POST['region_id'];

	if(isset($_POST['create_date']))
		$create_date = $_POST['create_date'];

	if(isset($_POST['registry_type']))
		$registry_type = $_POST['registry_type'];

	if(isset($_POST['filename']))
		$filename = $_POST['filename'];

	if(isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name']))
	{
		$file = $_FILES['file']['tmp_name'];
		$content = file_get_contents($file);
	} else {
		echo json_encode(array('error' => ERROR_FILE));
		echo $_FILES['file']['error'];
		exit();
	}
	

	$first_line = strstr($content, PHP_EOL, TRUE);

	$count_of_columns = substr_count($first_line, ';');
	if($count_of_columns > 0)
		$count_of_columns++;

	if(mb_check_encoding($content,"WINDOWS-1251"))
	{
		$content = iconv('cp1251', 'utf-8', $content);
	}
	$content = trim($content);
	//$content = iconv(mb_detect_encoding($content, array('utf-8', 'cp1251'), FALSE), 'utf-8', $content);
	//$content = iconv('cp1251', 'utf-8', $content);
	/*print_r();
	echo '--<br>';
	print_r(mb_check_encoding($content,"ASCII"));
	echo '--<br>';
	print_r(mb_check_encoding($content,"WINDOWS-1252"));
	echo '--<br>';
	print_r(mb_check_encoding($content,"UTF-8"));
	echo '--<br>';
	print_r(mb_check_encoding($content,"ISO-8859-1"));
	echo '--<br>';*/
	
	$content = pg_escape_string($content);

	if($registry_type == 1) //УПП
		$query = "select * from amc_load_csv_upp('${content}', '${create_date}', $region_id, $user_id)";

	if($registry_type == 2) //МЗЛ
		$query = "select * from amc_load_csv_mzl('${content}', '${create_date}', $region_id, $user_id)";

	$result = @pg_query($dbconn, $query);
	if($result)
	{
		$result = pg_fetch_array($result)[0];
		if($result)
			echo json_encode(array('error' => ERROR_CSV, 'csv' => chr(0xEF) . chr(0xBB) . chr(0xBF) . $result), JSON_UNESCAPED_UNICODE);
		else
			echo json_encode(array('error' => NO_ERROR));

	} else 
	{
		echo json_encode(array('error' => ERROR_SQL, 'text' => 'Ошибка запроса: ' . pg_last_error()), JSON_UNESCAPED_UNICODE);
	}
?>