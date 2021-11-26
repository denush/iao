<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    // cache for 1 day
	}
	$dbconn = pg_connect(
		//"host=192.168.0.13 port=5433 dbname=czl_455_fz user=postgres password=abudsc.lfpfktptnt"
		// "host=192.168.0.81 port=5432 dbname=czl_455_fz user=postgres password=123456"
		//"host=192.168.0.81 port=5432 dbname=czl_455_fz user=postgres password=kcc_lkz_dct[" //LOCAL
		"host=172.26.0.3 port=5432 dbname=czl_455_fz user=postgres password=hfp]t,bcm10[ezvb" //MSK
	// ) or die('Could not connect: ' . pg_last_error());
	) or die('-1');
?>