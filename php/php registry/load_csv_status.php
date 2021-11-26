<?php 

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
	error_reporting( E_ALL );
	session_start();

	$response = array();

	if(isset($_SESSION['status']))
	{
		if($_SESSION['status'])
			$response['status'] = $_SESSION['status'];
		if(isset($_SESSION['loaded_csv']) && $_SESSION['loaded_csv'])
		{
			$response['registry_type'] = $_SESSION['registry_type'];
			$response['loaded_csv'] = $_SESSION['loaded_csv'];
		}

		echo json_encode($response);
		exit();
	} else {
		$response = array(
			'status' => 'IDLE'
		);
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}
	/*

	if(isset($_SESSION['in_work']) && $_SESSION['in_work'])
	{
		if(isset($_SESSION['stop_uploading']) && $_SESSION['stop_uploading'])
		{
			$response = array(
				'status' => 'STOPPING'
			);
		} else {
			$response = array(
				'status' => 'IN_WORK',
				'total_lines' => $_SESSION['total_csv_lines'],
				'current_line' => $_SESSION['current_csv_progress']
			);
		}
	} else 
	{
		if(isset($_SESSION['response']) && $_SESSION['response'])
		{
			$section_problem = false;
			if(isset($_SESSION['section_problem']))
				$section_problem = $_SESSION['section_problem'];
			
			$response = array(
				'status' => 'COMPLETE',
				'section_problem' => $section_problem,
				'response' => $_SESSION['response']
			);
			//$response['response']['csv'] = iconv('utf-8', 'cp1251', $response['response']['csv']);
			$_SESSION['response'] = null;
		} else 
		{
			$response = array(
				'status' => 'IDLE'
			);
		}		
	}

	echo json_encode($response, JSON_UNESCAPED_UNICODE);
*/
?>