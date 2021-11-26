<?php
	const NO_ERROR = 0;
	const ERROR_CSV = 1;
	const ERROR_SQL = 2;
	const ERROR_FILE = 3;
	const LINES_PART_SIZE = 50;
	const UPP_HEAD = 'Лесничество;Участковое лесничество;Урочище (дача);Квартал;Выдел;Площадь выдела, га;Л/п выдел;широта;долгота;Площадь л/п выдела, га;Год лесоустройства;Целевое назначение лесов (код);Категория защитных лесов (код);ОЗУ;Аренда;состав;возраст;полнота;запас на 1 га;Источник данных*;Дата (год) обследования участка или создания первичного документа;Причина ослабления насаждений (код);Повреждаемая порода;СКС** породы;СКС насаждения;% общего отпада по породе (по запасу);% текущего отпада по породе (по запасу);% заселённых (повреждённых) деревьев (по запасу);Примечание;Поля с ошибками';
	const MZL_HEAD = 'Лесничество;Участковое лесничество;Урочище (дача);Квартал;Выдел;Площадь выдела, га;Л/п выдел;широта;долгота;Площадь л/п выдела, га;Целевое назначение лесов (код);Категория защитных лесов (код);ОЗУ;Причина повреждения насаждения (код);Вид рекомендуемого МЗЛ (код);Площадь МЗЛ, га;Полнота насаждения (фактическая);Повреждаемая порода;Процент выборки;Остаточная полнота;Количество выбираемых деревьев (для РАД);Приоритет проведения МЗЛ (код);номер Акта ЛПО;дата составления Акта;Рекомендуемый срок проведения МЗЛ;Примечание;Поля с ошибками';

	set_time_limit(0); //no limit
	ini_set('upload_max_filesize', '40M');
	ini_set('post_max_size', '40M');
	ini_set('memory_limit', '256M');

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

	//ini_set('session.use_only_cookies', false);
	//ini_set('session.use_cookies', false);
	//ini_set('session.use_trans_sid', false);
	//ini_set('session.cache_limiter', null);

	session_start();

	if(!isset($_SESSION['registry_user_id']))
		exit();
	else
		$user_id = $_SESSION['registry_user_id'];

	session_write_close();

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

	if(isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name']))
	{
		$file = $_FILES['file']['tmp_name'];
		$filename = $_FILES['file']['name'];
		$content = file_get_contents($file);
	} else {
		echo json_encode(array('error' => ERROR_FILE));
		echo $_FILES['file']['error'];

		/*session_start();
		$_SESSION['in_work'] = false;
		$_SESSION['response'] = array('error' => ERROR_FILE);
		session_write_close();*/

		exit();
	}
	
	if(mb_check_encoding($content,"WINDOWS-1251"))
	{
		$content = iconv('cp1251', 'utf-8', $content);
	}
	$content = trim($content);

	$lines = preg_split('/\r\n|\r|\n/', $content);
	if($registry_type == 1) //УПП
		$csv_head = UPP_HEAD;
	if($registry_type == 2) //МЗЛ
		$csv_head = MZL_HEAD;

	$response = array();
	array_push($response, $csv_head);

	$lines_count = count($lines);
	$offset = 1;
	$has_error = false;

	apache_setenv('no-gzip', '1');
	ini_set('session.use_only_cookies', false);
	ini_set('session.use_cookies', false);
	ini_set('session.use_trans_sid', false);
	ini_set('session.cache_limiter', null);

	session_start();
	$_SESSION['in_work'] = true;
	$_SESSION['response'] = null;
	$_SESSION['current_csv_progress'] = 0;
	$_SESSION['section_problem'] = false;
	$_SESSION['total_csv_lines'] = $lines_count - 1;
	session_write_close();

	ob_start();
	echo json_encode(['total_lines' => $lines_count - 1]);
	$size = ob_get_length();

	header("Content-Length: $size");
	header('Connection: close');

	ob_end_flush();
	ob_flush();
	flush();

	foreach ($lines as $key => $line_str) {
		//$lines[$key] = trim($line_str);
		$section = explode(';', $line_str)[4];
		if(strpos($section, ',') || strpos($section, '-'))
		{
			session_start();
			$_SESSION['section_problem'] = true;
			session_write_close();
			break;
		}
	}

	while($offset < $lines_count)
	{
		$part = array_slice($lines, $offset, LINES_PART_SIZE);
		$content = pg_escape_string(implode("\r\n", $part));

		if($registry_type == 1) //УПП
			$query = "select * from amc_load_csv_upp('${content}', '${create_date}', $region_id, $user_id, true)";
		if($registry_type == 2) //МЗЛ
			$query = "select * from amc_load_csv_mzl('${content}', '${create_date}', $region_id, $user_id, true)";

		//file_put_contents('log.log', $query, FILE_APPEND);
		$result = @pg_query($dbconn, $query);
		if($result)
		{
			$result = trim(pg_fetch_array($result)[0]);
			if($result)
			{
				$has_error = true;
				array_push($response, $result);
			}
		}
		$offset = $offset + LINES_PART_SIZE;

		session_start();
		if(isset($_SESSION['stop_uploading']) && $_SESSION['stop_uploading'])
		{
			$_SESSION['stop_uploading'] = false;
			$_SESSION['in_work'] = false;
			$_SESSION['response'] = null;
			exit();
		} else 
		{
			$_SESSION['current_csv_progress'] = $offset - 1;
		}
		session_write_close();
	}

	if($has_error)
	{
		$result = implode("\r\n", $response);
		//$result = (chr(0xFF) . chr(0xFE) . mb_convert_encoding( $result, 'UTF-16LE', 'UTF-8'));
		//$result = mb_convert_encoding( $result, 'UTF-16LE', 'UTF-8');
		session_start();
		$_SESSION['in_work'] = false;
		$_SESSION['response'] = array('error' => ERROR_CSV, 'csv' => $result, 'filename' => $filename);
		//$_SESSION['response'] = array('error' => ERROR_CSV, 'csv' => 123, 'filename' => $filename);
		//$_SESSION['csv'] = chr(0xEF) . chr(0xBB) . chr(0xBF) . $result;
		$_SESSION['csv'] = iconv('UTF-8', 'Windows-1251//TRANSLIT', $result);
		$_SESSION['csv_name'] = mb_substr($filename, 0, mb_strlen($filename) - 4) . '_ошибки.csv';
		session_write_close();
	} else {
		session_start();
		$_SESSION['in_work'] = false;
		$_SESSION['response'] = array('error' => NO_ERROR, 'filename' => $filename);
		session_write_close();
	}
?>