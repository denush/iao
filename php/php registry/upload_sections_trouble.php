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
	if(isset($_SESSION['registry_user_id']) && isset($_SESSION['table_id']) && isset($_POST['json']) && $_SESSION['status'] != 'CHECKING_CSV')
	{
		$table_id = $_SESSION['table_id'];
		$json = $_POST['json'];
		$user_id = $_SESSION['registry_user_id'];
		$_SESSION['status'] = 'CHECKING_CSV';
	}
	else
		exit();
	session_write_close();

	$query = "select * from amc_json_csvreg('${table_id}', '${json}', ${user_id});";
	$res = pg_query($dbconn, $query);

	session_start();
	$_SESSION['status'] = 'NO_TROUBLE_SECTIONS';
	session_write_close();

?>