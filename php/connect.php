<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header('Content-type: text/html; charset=utf-8');
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    // cache for 1 day
}

$dbconn = pg_connect(
	"host=172.26.24.104 port=5432 dbname=iao user=postgres password=Pjataevas_to_iao" // 
) or die('-1');