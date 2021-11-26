<?php
	include './connect.php';
	session_start();

		if (!empty($_SESSION['registry_auth']) and $_SESSION['registry_auth']) {
			echo $_SESSION['registry_user_id'];
			session_write_close();
		}
		else {
			if ( !empty($_COOKIE['registry_user_id']) and !empty($_COOKIE['registry_key']) ){
				$user_id = $_COOKIE['registry_user_id'];
				$key = $_COOKIE['registry_key'];				

				$query = "SELECT * FROM info_users_registry WHERE id = '" . $user_id . "' AND cookie = '" . $key . "'";
				$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());				

				if (pg_num_rows($result) == 1){

					$user = pg_fetch_array($result);

					$_SESSION['registry_auth'] = 1;
					$_SESSION['registry_user_id'] = $user['id'];

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
?>