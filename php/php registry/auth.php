<?php
	include './connect.php';

// Выполнение SQL запроса
	$login = $_POST['login'];
	$password = $_POST['password'];
	$remember = $_POST['remember'];

	if (is_string($login) && is_string($password)) 
	{
		$query = "select * from czl_check_pass_registry('${login}', '${password}')";
		$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
		$user_id = pg_fetch_result($result, 0);
		if($user_id == -1)
		{
			echo '-1';
			exit();
		}

		$query = "select * from info_users_registry where id = ${user_id}";
		$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
		$user = pg_fetch_array($result);
	}
	else 
	{
		echo "Error";
		exit();
	}

	$hash = $user['pass'];

	if (password_verify($password, $hash)) {
		session_start();
		$_SESSION['registry_auth'] = true;
		$_SESSION['registry_user_id'] = $user['id'];
		// $_SESSION['user_name'] = $user['name'];

		session_write_close();

		if ($remember == 'true'){
			$randomString = generateRandomString();

			$key = password_hash($randomString, PASSWORD_DEFAULT);

			setcookie('registry_user_id', $user['id'], time() + 60 * 60 * 24 * 30, '/');
			setcookie('registry_key', $key, time() + 60 * 60 * 24 * 30, '/');

			$query = "UPDATE info_users_registry SET cookie = '" . $key . "' WHERE login = '" . $login . "'";
			pg_query($dbconn, $query);
		}

		echo $user["id"];		
	}
	else {
		echo '-1';
	}
	
	//echo json_encode($user, JSON_UNESCAPED_UNICODE);
	
	pg_free_result($result);

	// Закрытие соединения

	pg_close($dbconn);

	function generateRandomString()
	{
		$result = '';
		$resultLength = 8; //длина строки
		for($i=0; $i<$resultLength; $i++)
		{
			$result .= chr(mt_rand(33,126)); //символ из ASCII-table
		}
		return $result;
	}
?>