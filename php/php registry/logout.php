<?php
	include './connect.php';

	if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    // cache for 1 day
	}

	//Если переменная auth из сессии не пуста и равна true, то...
	session_start();
	if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth'])
	{
		
		//session_destroy(); //разрушаем сессию для пользователя
		
		$_SESSION['registry_auth'] = null;
		$_SESSION['registry_user_id'] = null;

		$_SESSION['registry_type'] = null;
		$_SESSION['status'] = 'IDLE';
		unset($_SESSION['loaded_csv']);

		if(isset($_SESSION['table_id']))
		{
			$table_id = $_SESSION['table_id'];
			$_SESSION['table_id'] = null;
			$query = "drop table tmp_tabs.${table_id};";
			pg_query($dbconn, $query);
		}
		

		//Удаляем куки авторизации путем установления времени их жизни на текущий момент:
		setcookie('registry_user_id', '', time(), '/'); //удаляем логин
		setcookie('registry_key', '', time(), '/'); //удаляем ключ
	}
?>