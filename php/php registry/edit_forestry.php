<?php 
	include './connect.php';

	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}


	session_start();
	if(!isset($_SESSION['registry_user_id']) || $_SESSION['registry_user_id'] != 1)
		exit();

	session_write_close();

	$forestry_id = $_GET['id'];
	$forestry_name = $_GET['name'];

	$query = "select from czl_edit_forestry($forestry_id, '${forestry_name}');";
	$res = pg_query($dbconn, $query);
	
	$error = null;
	if($res === FALSE)
		$error = pg_last_error($dbconn);
	
	echo json_encode(array('Error' => $error));
?>