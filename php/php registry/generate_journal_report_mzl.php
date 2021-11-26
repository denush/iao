<?php

/** Error reporting */
//error_reporting(0);
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

$query = "SELECT * FROM czl_get_registry('01.01.0001','12.12.9999',2) where region_id = ${region_id}";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());
$journal = pg_fetch_all($result);

$current_date = date('d.m.Y');

$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/form_mzl.xlsx');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()
  ->setCellValue('B1', $region_name)
  ->setCellValue('B2', $current_date)
  ->setCellValue('X5', 'Сформировано програмным модулем версии от ' . $current_date);

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
      ->setCellValue('K' . $current_row, $row['forest_purpose_id'])
      ->setCellValue('L' . $current_row, $row['protective_cat_id'])
      ->setCellValue('M' . $current_row, $row['ozu'])
      ->setCellValue('N' . $current_row, $row['damage_reasons'])
      ->setCellValue('O' . $current_row, $row['action_types'])
      ->setCellValue('P' . $current_row, $row['s_action_type'])
      ->setCellValue('Q' . $current_row, $row['forest_density_or_survival'])
      ->setCellValue('R' . $current_row, $row['species_name'])
      ->setCellValue('S' . $current_row, $row['sample_of_sanitation'])
      ->setCellValue('T' . $current_row, $row['final_density'])
      ->setCellValue('U' . $current_row, $row['number_of_trees'])
      ->setCellValue('V' . $current_row, $row['action_priority_id'])
      ->setCellValue('W' . $current_row, $row['action_act_number'])
      ->setCellValue('X' . $current_row, $row['action_act_date'])
      ->setCellValue('Y' . $current_row, $row['suggested_date']);

      $current_row++;
  }

  $objPHPExcel->getActiveSheet()
    ->getStyle('A9:Y' . ($current_row - 1))
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
    ->setCellValue('B' . ($current_row + 6), $user_name)
    ->setCellValue('C' . ($current_row + 6), $_GET['user_position'])
    ->setCellValue('B' . ($current_row + 8), $_GET['boss_name'])
    ->setCellValue('C' . ($current_row + 8), $_GET['boss_position']);

$objPHPExcel->getActiveSheet()
    ->setCellValue('A' . ($current_row + 2), 'Примечания')
    ->setCellValue('B' . ($current_row + 3), 'Вид МЗЛ: (511 - ССР, 512 - ВСР, 534 - УНД; 301 - ВНН; 370 - ЛПО-И, 371 - ЛПО-В)')
    ->setCellValue('A' . ($current_row + 6), 'Исполнитель')
    ->setCellValue('B' . ($current_row + 7), '(ФИО)')
    ->setCellValue('C' . ($current_row + 7), '(Должность)')

    ->setCellValue('A' . ($current_row + 8), 'Руководитель')
    ->setCellValue('B' . ($current_row + 9), '(ФИО)')
    ->setCellValue('C' . ($current_row + 9), '(Должность)');

$objPHPExcel->getActiveSheet()
    ->getStyle('B' . ($current_row + 2))
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
    ->getStyle('B' . ($current_row + 6) . ':C' . ($current_row + 6))
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
    ->getStyle('B' . ($current_row + 9) . ':C' . ($current_row + 9))
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

/*$objPHPExcel->getActiveSheet()
    ->setCellValue('A' . ($current_row + 2), 'Примечания:')
    ->setCellValue('A' . ($current_row + 3), 'ПРОВО – мероприятия по предотвращению распространения вредных организмов')
    ->setCellValue('A' . ($current_row + 4), '*   МЗЛ- мероприятия по защите лесов')
    ->setCellValue('A' . ($current_row + 5), '** - Приоритет проведения МЗЛ: по аналогии с приоритетом проведения ЛПО.')
    ->setCellValue('A' . ($current_row + 6), '***  - Назначение мероприятий: номер Акта ЛПО - номер Акта ЛПО в реестре актов, который ведёт ОИВ субъекта РФ')
    ->setCellValue('A' . ($current_row + 7), '                                                дата назначения - дата составления Акта исполнителем (дата проведения ЛПО)')
    ->setCellValue('A' . ($current_row + 8), '*** - Оптимальный срок проведения МЗЛ: оптимальный срок проведения МЗЛ в соответствии с требованиями действующих НПА и методических документов')
    ->setCellValue('A' . ($current_row + 10), 'Исполнитель ____________ (ФИО) ___________(должность)             Дата составления __________')
    ->setCellValue('A' . ($current_row + 12), 'Руководитель ____________ (ФИО)');*/



/*
$current_row = 8;
$count = count($arr);

$rf_act_total = 0;
$rf_act_is_draft = 0;
$rf_act_is_not_draft = 0;
$rf_conclusion_total = 0;
$rf_conclusion_is_draft = 0;
$rf_conclusion_is_not_draft = 0;

$fo_act_total = 0;
$fo_act_is_draft = 0;
$fo_act_is_not_draft = 0;
$fo_conclusion_total = 0;
$fo_conclusion_is_draft = 0;
$fo_conclusion_is_not_draft = 0;

foreach ($arr as $key => $value) {
  $objPHPExcel->getActiveSheet()
  ->setCellValue('A' . $current_row, $value['user_name'])
  ->setCellValue('B' . $current_row, $value['act_total'])
  ->setCellValue('C' . $current_row, $value['act_is_draft'])
  ->setCellValue('D' . $current_row, $value['act_is_not_draft'])
  ->setCellValue('E' . $current_row, $value['conclusion_total'])
  ->setCellValue('F' . $current_row, $value['conclusion_is_draft'])
  ->setCellValue('G' . $current_row, $value['conclusion_is_not_draft']);

  $rf_act_total += $value['act_total'];
  $rf_act_is_draft += $value['act_is_draft'];
  $rf_act_is_not_draft += $value['act_is_not_draft'];
  $rf_conclusion_total += $value['conclusion_total'];
  $rf_conclusion_is_draft += $value['conclusion_is_draft'];
  $rf_conclusion_is_not_draft += $value['conclusion_is_not_draft'];

  $fo_act_total += $value['act_total'];
  $fo_act_is_draft += $value['act_is_draft'];
  $fo_act_is_not_draft += $value['act_is_not_draft'];
  $fo_conclusion_total += $value['conclusion_total'];
  $fo_conclusion_is_draft += $value['conclusion_is_draft'];
  $fo_conclusion_is_not_draft += $value['conclusion_is_not_draft'];

  if((array_key_exists($key + 1, $arr) && $arr[$key + 1]['fo'] != $value['fo']) || !array_key_exists($key + 1, $arr))
  {
    $current_row++;
    $objPHPExcel->getActiveSheet()
      ->setCellValue('A' . $current_row, $value['fo'])
      ->setCellValue('B' . $current_row, $fo_act_total)
      ->setCellValue('C' . $current_row, $fo_act_is_draft)
      ->setCellValue('D' . $current_row, $fo_act_is_not_draft)
      ->setCellValue('E' . $current_row, $fo_conclusion_total)
      ->setCellValue('F' . $current_row, $fo_conclusion_is_draft)
      ->setCellValue('G' . $current_row, $fo_conclusion_is_not_draft);

    $objPHPExcel->getActiveSheet()
    ->getStyle('A' . $current_row)
    ->applyFromArray(
       array('font' => array(
                'bold' => true,
                'italic' => true
              ),
            )
     );

    $fo_act_total = 0;
    $fo_act_is_draft = 0;
    $fo_act_is_not_draft = 0;
    $fo_conclusion_total = 0;
    $fo_conclusion_is_draft = 0;
    $fo_conclusion_is_not_draft = 0;
  }
  $current_row++;
}

  $objPHPExcel->getActiveSheet()
    ->setCellValue('A' . $current_row, 'Итого по РФ')
    ->setCellValue('B' . $current_row, $rf_act_total)
    ->setCellValue('C' . $current_row, $rf_act_is_draft)
    ->setCellValue('D' . $current_row, $rf_act_is_not_draft)
    ->setCellValue('E' . $current_row, $rf_conclusion_total)
    ->setCellValue('F' . $current_row, $rf_conclusion_is_draft)
    ->setCellValue('G' . $current_row, $rf_conclusion_is_not_draft);

  $objPHPExcel->getActiveSheet()
    ->getStyle('A' . $current_row)
    ->applyFromArray(
       array('font' => array(
                'bold' => true,
                'italic' => true
              ),
            )
     );

    $objPHPExcel->getActiveSheet()
    ->getStyle('A8:G' . $current_row)
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
            'size' => 11
          )
        )
     );

    $objPHPExcel->getActiveSheet()
    ->getStyle('A8:A' . $current_row)
    ->applyFromArray(
        array(
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
          )
        )
    );

      
*/
////////////////////

$file_name = $current_date . '_' . $region_id .'_Реестр МЗЛ.xlsx';
//$file_name = 'Количество актов и заключений (' . $start . ' - ' . $end . ').xlsx';

// Rename worksheet
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getProperties()->setCreator($user_name)->setLastModifiedBy("");
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(90);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');


// Очистка результата

//pg_free_result($result);

// Закрытие соединения

pg_close($dbconn);

exit;