<?php

include '../connect.php';
session_start();

if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
	echo $_SESSION['user_id'];
	session_write_close();
} 
// else {
// 	echo -1;
// }

else {
	if ( !empty($_COOKIE['user_id']) and !empty($_COOKIE['key']) ) {
		$user_id = $_COOKIE['user_id'];
		$key = $_COOKIE['key'];				

		$query = "SELECT * FROM info_users WHERE id = '" . $user_id . "' AND cookie = '" . $key . "'";
		$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());				

		if (pg_num_rows($result) == 1) {

			$user = pg_fetch_array($result);

			$_SESSION['auth'] = 1;
			$_SESSION['user_id'] = $user['id'];

			echo $user['id'];

			session_write_close();
		}
		else {
			echo -1;
		}
	}
	else {
		echo -1;
	}
}
