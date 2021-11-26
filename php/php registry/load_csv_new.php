<?php 
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
	$table_id = null;
	session_start();
	if(isset($_SESSION['registry_user_id']))
	{
		if(isset($_SESSION['table_id']) && $_SESSION['table_id'])
			$table_id = $_SESSION['table_id'];
		$user_id = $_SESSION['registry_user_id'];
	}
	else
		exit();

	$count = count($_FILES['files']['name']);
	$regions = explode(',', $_POST['regions']);
	$dates = explode(',', $_POST['dates']);

	$registry_type = intval($_POST['registry_type']);
	if($table_id == null)
	{
		$result = pg_query($dbconn, "select * from amc_create_tmpcsv(${registry_type});");
		$table_id = pg_fetch_array($result)[0];
		$_SESSION['table_id'] = $table_id;
		$_SESSION['loaded_csv'] = array();
		$_SESSION['registry_type'] = $registry_type;
	}
	
	$_SESSION['status'] = 'UPLOADING_CSV';
	session_write_close();

	for ($i = 0; $i < $count; $i++) {
	    //echo 'Name: ' . $_FILES['files']['name'][$i] . ' - ' . $regions[$i] . ' - ' . $dates[$i] . '<br/>';
	    
	    session_start();
		$_SESSION['loaded_csv'][] = array('name' => $_FILES['files']['name'][$i], 'region_id' => $regions[$i], 'date' => $dates[$i]);
		session_write_close();

	    $region = $regions[$i];
	    $date = $dates[$i];
		
	    $content = file_get_contents($_FILES['files']['tmp_name'][$i]);
	    if(mb_check_encoding($content,"WINDOWS-1251"))
		{
			$content = iconv('cp1251', 'utf-8', $content);
		}

		$content = trim($content);
		$lines = preg_split('/\r\n|\r|\n/', $content);
		array_shift($lines);
		//$lines_count = count($lines);
		$content = pg_escape_string(implode("\r\n", $lines));

		$query = "select * from amc_fill_tmpcsv('${content}', '${table_id}', '${date}', ${region});";
		//file_put_contents('log.log', $query);
		$res = pg_query($dbconn, $query);
	}

	session_start();
	$_SESSION['status'] = 'UPLOADING_CSV_COMPLETE';
	session_write_close();

	//echo 'ok'
	echo $table_id;
?>