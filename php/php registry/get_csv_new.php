<?php 
	include './connect.php';
	date_default_timezone_set('Europe/Moscow');

	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}

	session_start();

	if(isset($_SESSION['table_id']) && $_SESSION['table_id'])
	{
		$table_id = $_SESSION['table_id'];
		//header('Content-Type: text/csv; charset=w', true);
		$query = "select * from amc_return_tmpcsv('${table_id}')";
		//file_put_contents('log.log', $query);
		$res = pg_query($dbconn, $query);
		$csv = pg_fetch_array($res)[0];

		$filename = date('d.m.Y H:i:s') . '_ошибки.csv';
		if(isset($_SESSION['loaded_csv']) && count($_SESSION['loaded_csv']) == 1)
		{
			$filename = mb_substr($_SESSION['loaded_csv'][0]['name'], 0, -4) . '_ошибки.csv';
		}

		header('Content-Type: text/csv;');
		header('Content-Disposition: attachment; filename=' . $filename); 
		echo iconv('UTF-8', 'Windows-1251//TRANSLIT', $csv);
		//$_SESSION['csv'] = null;
	}		

?>