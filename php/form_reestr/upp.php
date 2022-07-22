<?php

ini_set("memory_limit", "2000M");

ini_set('max_execution_time', '7000'); //300 seconds = 5 minutes
set_time_limit(0);

// ini_set("display_errors", "1");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once '../connect.php';
require_once '../vendor/autoload.php';

$region_id = $_POST['region_id'];

// Получение таблицы реестра УПП
if ($region_id === '0') {
  $query = "SELECT * from czl_get_app_1_1_from_main_iao(true)"; // true - включить в таблицу строку "итого"
} else {
  $query = "SELECT * from czl_get_app_1_1_from_main_iao({$region_id}, true)"; // true - включить в таблицу строку "итого"
}

// $query = "SELECT * from czl_get_app_1_1_from_main_iao({$region_id}, true)"; // true - включить в таблицу строку "итого"
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$table_upp = [];
while ($result_row = pg_fetch_assoc($result)) {
  $table_upp[] = $result_row;
}

$writer = new XLSXWriter();

$sheet_name = 'upp';

$header = [
  'a' => 'string',
  'b' => 'string',
  'c' => 'string',
  'd' => 'string',
  'e' => 'string',
  'f' => 'string',
  'g' => 'string',
  'h' => 'string',
  'i' => 'string',
  'j' => 'string',
  'k' => 'string',
  'l' => 'string',
  'm' => 'string',
  'n' => 'string',
  'o' => 'string',
  'p' => 'string',
  'q' => 'string',
  'r' => 'string',
  's' => 'string',
  't' => 'string',
  'u' => 'string',
  'v' => 'string',
  'w' => 'string',
  'x' => 'string',
  'y' => 'string'
];

$col_widths = [
  0 => 18,
  1 => 18,
  2 => 18,
  3 => 7,
  4 => 7,
  5 => 7,
  6 => 7,
  7 => 11,
  8 => 11,
  9 => 11,
  10 => 6,
  11 => 6,
  12 => 6,
  13 => 6,
  14 => 16,
  15 => 6,
  16 => 12,
  17 => 6,
  18 => 6,
  19 => 6,
  20 => 11,
  21 => 12,
  22 => 7,
  23 => 7,
  24 => 16
];

$writer->writeSheetHeader($sheet_name, $header, $col_options = [ 'suppress_row' => true, 'widths' => $col_widths ]);

// $document_header = [
//   // 'a' => '',
//   // 'b' => '',
//   // 'c' => '',
//   // 'd' => '',
//   // 'e' => '',
//   // 'f' => '',
//   // 'g' => '',
//   // 'h' => '',
//   // 'i' => '',
//   // 'j' => '',
//   // 'k' => '',
//   // 'l' => '',
//   // 'm' => '',
//   // 'n' => '',
//   // 'o' => '',
//   // 'p' => '',
//   // 'q' => '',
//   // 'r' => '',
//   // 's' => '',
//   // 't' => '',
//   // 'u' => '',
//   // 'v' => '',
//   // 'w' => '',
//   // 'x' => '',
//   24 => 'Приложение 1.1'
// ];

// $writer->writeSheetRow($sheet_name, $document_header, $row_options = [ 'valign'=> 'center', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'height' => 75,'wrap_text' => true ]);

$table_header1 = [
  'a' => 'Лесничество',
  'b' => 'Участковое лесничество',
  'c' => 'Урочище',
  'd' => 'Номер лесного квартала',
  'e' => 'Номер лесотаксационного выдела',
  'f' => 'Площадь лесотаксационного выдела, га',
  'g' => 'Обозначение части лесотаксационного выдела (лесопатологического выдела)',
  'h' => 'Площадь лесотаксационного выдела или его части (лесопатологического выдела), га',
  'i' => 'Место расположение выдела или его части, географические координаты центра участка *',
  'j' => '',
  'k' => 'Год лесоустройства',
  'l' => 'Целевое назначение лесов',
  'm' => 'Категория защитных лесов (код)',
  'n' => 'Отметка об ОЗУ*',
  'o' => 'Отметка об аренде*',
  'p' => 'Источник данных',
  'q' => 'Дата проведения наблюдения (обследования) или создания первичного документа',
  'r' => 'Причина (-ны) повреждения насаждения',
  's' => 'Повреждаемая (-мые) порода (-ды)',
  't' => 'Общий отпад насаждения (усыхания) по запасу, %',
  'u' => 'Степень повреждения (поражения) насаждения, %',
  'v' => 'Дата внесения данных в реестр*',
  'w' => 'Отметка об актуальности данных',
  'x' => 'Отметка о необходимости включения в реестр МЗЛ',
  'y' => 'Примечание'
];

$table_header2 = [
  'a' => '',
  'b' => '',
  'c' => '',
  'd' => '',
  'e' => '',
  'f' => '',
  'g' => '',
  'h' => '',
  'i' => 'широта',
  'j' => 'долгота',
  'k' => '',
  'l' => '',
  'm' => '',
  'n' => '',
  'o' => '',
  'p' => '',
  'q' => '',
  'r' => '',
  's' => '',
  't' => '',
  'u' => '',
  'v' => '',
  'w' => '',
  'x' => '',
  'y' => ''
];

$table_header3 = [
  'a' => '1',
  'b' => '2',
  'c' => '3',
  'd' => '4',
  'e' => '5',
  'f' => '6',
  'g' => '7',
  'h' => '8',
  'i' => '9',
  'j' => '10',
  'k' => '11',
  'l' => '12',
  'm' => '13',
  'n' => '14',
  'o' => '15',
  'p' => '16',
  'q' => '17',
  'r' => '18',
  's' => '19',
  't' => '20',
  'u' => '21',
  'v' => '22',
  'w' => '23',
  'x' => '24',
  'y' => '25'
];

foreach ($table_upp as $upp_row) {
  $data[] = [
    "a${$row}" => $upp_row['forestry'],
    "b${$row}" => $upp_row['localforestry'],
    "c${$row}" => $upp_row['subforestry'],
    "d${$row}" => $upp_row['area'],
    "e${$row}" => $upp_row['section'],
    "f${$row}" => $upp_row['s_section'],
    "g${$row}" => $upp_row['section_lp'],
    "h${$row}" => $upp_row['s_inspection'],
    "i${$row}" => $upp_row['lat'],
    "j${$row}" => $upp_row['lon'],
    "k${$row}" => $upp_row['forest_management_year'],
    "l${$row}" => $upp_row['forest_purpose_id'],
    "m${$row}" => $upp_row['protection_category_id'],
    "n${$row}" => $upp_row['ozu_id'],
    "o${$row}" => $upp_row['tenant'],
    "p${$row}" => $upp_row['data_source_1_1'],
    "q${$row}" => $upp_row['inspection_date'],
    "r${$row}" => $upp_row['main_damage_reason_id_1olpm'],
    "s${$row}" => $upp_row['damaged_species_1olpm'],
    "t${$row}" => $upp_row['common_loss'],
    "u${$row}" => $upp_row['drying_degree'],
    "v${$row}" => $upp_row['registry_insertion_date'],
    "w${$row}" => $upp_row['actualization_mode'],
    "x${$row}" => $upp_row['lzm_registry'],
    "y${$row}" => $upp_row['comments'],
  ];


}



// Создание файла
// $writer->writeSheet($data, $sheet_name);

$writer->writeSheetRow($sheet_name, $table_header1, $row_options = [ 'valign'=> 'center', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'height' => 75,'wrap_text' => true ]);
$writer->writeSheetRow($sheet_name, $table_header2, $row_options = [ 'valign'=> 'center', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'height' => 90,'wrap_text' => true ]);
$writer->writeSheetRow($sheet_name, $table_header3, $row_options = [ 'valign'=> 'center', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin' ]);

foreach($data as $row) {
  $writer->writeSheetRow($sheet_name, $row, $row_options = [ 'valign'=> 'center', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true ]);
}
	

$writer->markMergedCell($sheet_name, $start_row=0, $start_col=8, $end_row=0, $end_col=9);

$writer->markMergedCell($sheet_name, $start_row=0, $start_col=0, $end_row=1, $end_col=0);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=1, $end_row=1, $end_col=1);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=2, $end_row=1, $end_col=2);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=3, $end_row=1, $end_col=3);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=4, $end_row=1, $end_col=4);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=5, $end_row=1, $end_col=5);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=6, $end_row=1, $end_col=6);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=7, $end_row=1, $end_col=7);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=10, $end_row=1, $end_col=10);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=11, $end_row=1, $end_col=11);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=12, $end_row=1, $end_col=12);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=13, $end_row=1, $end_col=13);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=14, $end_row=1, $end_col=14);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=15, $end_row=1, $end_col=15);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=16, $end_row=1, $end_col=16);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=17, $end_row=1, $end_col=17);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=18, $end_row=1, $end_col=18);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=19, $end_row=1, $end_col=19);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=20, $end_row=1, $end_col=20);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=21, $end_row=1, $end_col=21);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=22, $end_row=1, $end_col=22);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=23, $end_row=1, $end_col=23);
$writer->markMergedCell($sheet_name, $start_row=0, $start_col=24, $end_row=1, $end_col=24);

$file = realpath('../../../files/temp_iao/') . '/upp.xlsx';// server
$writer->writeToFile($file);

if (file_exists($file)) {
  $xlsData = file_get_contents($file);

  $file_name = 'upp';
  $file_content = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'.base64_encode($xlsData);

  unlink($file);

  $response = array(
    'name' => $file_name,
    'file' => $file_content
  );

  echo json_encode($response);
  pg_close($dbconn);
}
else {
  echo 'error';
}