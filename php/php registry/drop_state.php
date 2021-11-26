<?php 
	set_time_limit(0); //no limit
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

	session_start();
	if(isset($_SESSION['registry_user_id']) && isset($_SESSION['table_id']))
	{
		$table_id = $_SESSION['table_id'];
		$_SESSION['table_id'] = null;
		$_SESSION['registry_type'] = null;
		$_SESSION['status'] = 'IDLE';
		unset($_SESSION['loaded_csv']);
		
		$query = "drop table tmp_tabs.${table_id};";
		pg_query($dbconn, $query);
	}
	else
		exit();
	session_write_close();

	echo 'ok';
?>