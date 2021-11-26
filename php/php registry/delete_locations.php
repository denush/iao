<?php 
	if (isset($_SERVER['HTTP_ORIGIN'])) {
	    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	    header('Access-Control-Allow-Credentials: true');
	    header('Access-Control-Max-Age: 86400');
	    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    // cache for 1 day
	}

	include './connect.php';
	session_start();

	function generateRandomString()
	{
		$result = '';
		$resultLength = 4; //длина строки
		for($i=0; $i<$resultLength; $i++)
		{
			$result .= chr(mt_rand(97,122)); //символ из ASCII-table
		}
		return $result;
	}

	$user_id = NULL;
	if(isset($_SESSION['registry_user_id']))
	{
		$user_id = intval($_SESSION['registry_user_id']);
	}

	$registry_type = $_POST['registry_type'];
	$delete_linked = $_POST['delete_linked'];

	$hide_reason = $_POST['hide_reason'] ? $_POST['hide_reason'] : NULL;
	$hide_reason_doc = NULL;

	if(isset($_FILES['file']))
		if(is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$hide_reason_doc = time() . '_' . generateRandomString() . '_' . $_POST['file_name'];
			move_uploaded_file($_FILES['file']['tmp_name'], './../docs_archive/' . $hide_reason_doc);
		}

	$ids = explode(',',$_POST['ids']);
	$json = array('hide_reason' => $hide_reason, 'hide_reason_doc' => $hide_reason_doc, 'ids' => $ids);
	$json = json_encode($json);
	
	$error = NULL;

	if($delete_linked == true) {
		if($registry_type == 1)
			$query = "select from amc_hide_registry_locations('${json}', ${user_id}, 2);";
		else
			$query = "select from amc_hide_registry_locations('${json}', ${user_id}, 1);";
	} else {
		$query = "select from amc_hide_registry_locations('${json}', ${user_id});";
	}
	
	$result = pg_query($dbconn, $query);
	
	if(!$result)
		$error = pg_last_error();

	echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
?>