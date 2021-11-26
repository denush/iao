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

	set_time_limit(0); //no limit
	ini_set('memory_limit', '2000M');
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/Moscow');
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
	/** Include PHPExcel */
	require_once dirname(__FILE__) . './lib/PHPExcel.php';
	require_once dirname(__FILE__) . './lib/PHPExcel/IOFactory.php';

	include './connect.php';

	// Create new PHPExcel object
	//$objPHPExcel = new PHPExcel();

	if(isset($_GET['region_id']))
	{
	  $region_id = intval($_GET['region_id']);
	} else 
	  exit();

	session_start();

	$user_id = $_SESSION['registry_user_id'];
	$query = "select name from info_users_registry where id = ${user_id}";
	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$user_name = pg_fetch_assoc($result)['name'];

	$query = "select * from info_regions where id = ${region_id}";
	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$region_name = pg_fetch_assoc($result)['name'];

	$query = "select * from amc_get_abbr_fo(${region_id});";
	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$fo_abbr = pg_fetch_assoc($result)['amc_get_abbr_fo'];

	$query = "SELECT * FROM czl_get_registry('01.01.0001','12.12.9999',1) where region_id = ${region_id}";
	$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
	$journal = pg_fetch_all($result);

	$current_date = date('d.m.Y');

	$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objPHPExcel->load('templates/form_upp.xlsx');
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()
	  ->setCellValue('B1', $region_name)
	  ->setCellValue('B2', $current_date)
	  ->setCellValue('AB5', 'Сформировано програмным модулем версии от ' . $current_date);

	$current_row = 9;

	if(is_array($journal))
	{
	  foreach ($journal as $key => $row) {
	    $objPHPExcel->getActiveSheet()
	      ->setCellValue('A' . $current_row, $row['forestry'])
	      ->setCellValue('B' . $current_row, $row['localforestry'])
	      ->setCellValue('C' . $current_row, $row['subforestry'])
	      ->setCellValue('D' . $current_row, $row['area'])
	      ->setCellValue('E' . $current_row, $row['section'])
	      ->setCellValue('F' . $current_row, $row['s'])
	      ->setCellValue('G' . $current_row, $row['section_lp'])
	      ->setCellValue('H' . $current_row, $row['latitude'])
	      ->setCellValue('I' . $current_row, $row['longitude'])
	      ->setCellValue('J' . $current_row, $row['s_lp'])
	      ->setCellValue('K' . $current_row, $row['forest_management_year'])
	      ->setCellValue('L' . $current_row, $row['forest_purpose_id'])
	      ->setCellValue('M' . $current_row, $row['protective_cat_id'])
	      ->setCellValue('N' . $current_row, $row['ozu'])
	      ->setCellValue('O' . $current_row, $row['lease'])
	      ->setCellValue('P' . $current_row, $row['forest_composition'])
	      ->setCellValue('Q' . $current_row, $row['forest_age'])
	      ->setCellValue('R' . $current_row, $row['forest_density_or_survival'])
	      ->setCellValue('S' . $current_row, $row['forest_volume'])
	      ->setCellValue('T' . $current_row, $row['data_source_id'])
	      ->setCellValue('U' . $current_row, $row['date_of_work'])
	      ->setCellValue('V' . $current_row, $row['damage_reasons'])
	      ->setCellValue('W' . $current_row, $row['species_name'])
	      ->setCellValue('X' . $current_row, $row['species_sks'])
	      ->setCellValue('Y' . $current_row, $row['forest_sks'])
	      ->setCellValue('Z' . $current_row, $row['common_species_loss'])
	      ->setCellValue('AA' . $current_row, $row['current_species_loss'])
	      ->setCellValue('AB' . $current_row, $row['infested_trees'])
	      ->setCellValue('AC' . $current_row, $row['registry_comment']);

	    $current_row++;
	  }

	  $objPHPExcel->getActiveSheet()
	    ->getStyle('A9:AC' . ($current_row - 1))
	    ->applyFromArray(
	        array(
	          'alignment' => array(
	              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
	          ),
	          'borders' => array(
	              'allborders' => array(
	                  'style' => PHPExcel_Style_Border::BORDER_THIN
	              )
	          ),
	          'font' => array(
	            'name' => 'Times New Roman',
	            'size' => 10
	          )
	        )
	      );
	}

$objPHPExcel->getActiveSheet()
    ->setCellValue('B' . ($current_row + 7), $user_name)
    ->setCellValue('C' . ($current_row + 7), $_GET['user_position'])
    ->setCellValue('B' . ($current_row + 9), $_GET['boss_name'])
    ->setCellValue('C' . ($current_row + 9), $_GET['boss_position']);

$objPHPExcel->getActiveSheet()
    ->setCellValue('A' . ($current_row + 4), 'Примечания')
    ->setCellValue('A' . ($current_row + 7), 'Исполнитель')
    ->setCellValue('B' . ($current_row + 8), '(ФИО)')
    ->setCellValue('C' . ($current_row + 8), '(Должность)')

    ->setCellValue('A' . ($current_row + 9), 'Руководитель')
    ->setCellValue('B' . ($current_row + 10), '(ФИО)')
    ->setCellValue('C' . ($current_row + 10), '(Должность)');

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 4))
    ->applyFromArray(
        array(
          'borders' => array(
              'bottom' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          ),
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
          )
        )
      );

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 7) . ':C' . ($current_row + 7))
    ->applyFromArray(
        array(
          'borders' => array(
              'bottom' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          ),
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
          )
        )
      );

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 9) . ':C' . ($current_row + 9))
    ->applyFromArray(
        array(
          'borders' => array(
              'bottom' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          ),
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
          )
        )
      );

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 8) . ':C' . ($current_row + 8))
    ->applyFromArray(
        array(
          'font' => array(
            'name' => 'Times New Roman',
            'size' => 8
          ),
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
          )
        )
      );

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 10) . ':C' . ($current_row + 10))
    ->applyFromArray(
        array(
          'font' => array(
            'name' => 'Times New Roman',
            'size' => 8
          ),
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
          )
        )
      );


	////////////////////

	$file_name =  $current_date . '_' . $region_id . '_Реестр УПП_СФОРМИРОВАН_СИСТЕМОЙ.xlsx';
	$rand_name = 'tmp_' . rand(100, 1000000) . '.xlsx';
	//$file_name = 'Количество актов и заключений (' . $start . ' - ' . $end . ').xlsx';

	// Rename worksheet
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getProperties()->setCreator($user_name)->setLastModifiedBy("");
	$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(90);
	// Redirect output to a client’s web browser (Excel2007)

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($rand_name);

	$ftp_user_name = 'mikhnenkovos';
	$ftp_user_pass = 'G4ekNT';

	// установка соединения
	$conn_id = ftp_ssl_connect('files.rcfh.ru', 21);
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	
	ftp_pasv($conn_id, true);
	ftp_raw($conn_id, 'OPTS UTF8 ON');

	$path = "./Реестр ГЛПМ/${fo_abbr}/${region_name}";

	if(!@ftp_put($conn_id, $path . '/УПП/' . $file_name, $rand_name, FTP_BINARY))
	{
		if(!@ftp_put($conn_id, $path . '/ОПП/' . $file_name, $rand_name, FTP_BINARY))
		{
			echo 'put problem';
			exit();
		}
	}

	unlink($rand_name);
?>