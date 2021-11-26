<?php

include '../connect.php';

// session_start();

// session_destroy(); //разрушаем сессию для пользователя

// 	//Удаляем куки авторизации путем установления времени их жизни на текущий момент:
// 	setcookie('user_id', '', time(), '/'); //удаляем логин
// 	setcookie('key', '', time(), '/'); //удаляем ключ

// session_write_close();

// exit;

$username = $_POST['username'];
$password = $_POST['password'];
$remember = $_POST['remember'];

if (!is_string($username) || !is_string($password)) {
	echo "Error";
	exit();
}

$username = pg_escape_string($username);
$password = pg_escape_string($password);

$query = "SELECT * from czl_check_pass('${username}', '${password}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
$user_id = pg_fetch_assoc($result)['czl_check_pass'];

if($user_id == -1) {
	echo '-1';
	exit();
}

$query = "SELECT * from info_users where id = ${user_id}";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
$user = pg_fetch_array($result);


$hash = $user['pass'];

if (password_verify($password, $hash)) {
	session_start();
	$_SESSION['auth'] = true;
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['user_name'] = $user['name'];

	session_write_close();

	if ($remember == 'true') {
		$randomString = generateRandomString();

		$key = password_hash($randomString, PASSWORD_DEFAULT);

		setcookie('user_id', $user['id'], time() + 60 * 60 * 24 * 30, '/');
		setcookie('key', $key, time() + 60 * 60 * 24 * 30, '/');

		$query = "UPDATE info_users SET cookie = '" . $key . "' WHERE id = '" . $user['id'] . "'";
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