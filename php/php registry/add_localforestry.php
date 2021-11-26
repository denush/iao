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
	if(!isset($_SESSION['registry_user_id']) || $_SESSION['registry_user_id'] != 1)
		exit();

	session_write_close();

	$forestry_id = $_GET['id'];
	$localforestry_name = $_GET['name'];

	$query = "select * from czl_add_localforestry($forestry_id, '${localforestry_name}');";
	$res = pg_query($dbconn, $query);
	$new_id = pg_fetch_result($res, 0, 0);

	$error = null;
	if($res === FALSE)
		$error = pg_last_error($dbconn);
	
	echo json_encode(array('Error' => $error, 'id' => $new_id));
?>