<?php

ini_set("memory_limit", "10000M");

ini_set('max_execution_time', '600'); //300 seconds = 5 minutes
set_time_limit(600);

// ini_set("display_errors", "1");
// error_reporting(E_ALL);

require_once '../connect.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$spreadsheet->getDefaultStyle()->applyFromArray([
	'font' => [
    'size' => 11,
    'name' => 'Calibri'
  ]
]);

$sheet = $spreadsheet->getActiveSheet();

$temp_table_id = $_POST['table_id'];

// while ($row = pg_fetch_assoc($result)) {
// 	$temp_table_template[] = $row;
// }

// Получение шаблона временной таблицы
$query = "SELECT * from sys_fields_list_forestries order by field_num";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$temp_table_template = [];
while ($row = pg_fetch_assoc($result)) {
	$temp_table_template[] = $row;
}

// Получение некорректных строк временной таблицы

$incorrect_rows_filter = "(is_row_correct=''false'') and (is_row_checked=''true'')";
$query = "SELECT * from czl_get_tmp_table_forestries('{$temp_table_id}', null, null, '{$incorrect_rows_filter}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$temp_table = [];
while ($row = pg_fetch_assoc($result)) {
	$temp_table[] = $row;
}

// echo json_encode($temp_table, JSON_UNESCAPED_UNICODE);
// exit;

$row = 1;

$letters = range('a', 'z');

function get_column_name($i) {
	global $letters;

	$integer_part = intdiv($i, 26); // целочисленное деление
	$remainder_part = $i % 26;

	$column_name = ( ($integer_part === 0) ? '' : $letters[$integer_part - 1] ) . $letters[$remainder_part];

	return $column_name;
}

function is_cell_incorrect($temp_table_row, $temp_table_column) {
	if ($temp_table_column['field_check'] && ($temp_table_row[$temp_table_column['field_check']] === '-1')) {
		return true;
	}
	return false;
}

for ($i = 0; $i < count($temp_table_template); ++$i) {
	$temp_table_column = $temp_table_template[$i];

	$column_name = get_column_name($i);

	$sheet->setCellValue($column_name.($row + 0), $temp_table_column['name']);
	$sheet->setCellValue($column_name.($row + 1), $temp_table_column['field_num']);
}

$row += 2;

$incorrect_count = 0;

foreach ($temp_table as $temp_table_row) {
	for ($i = 0; $i < count($temp_table_template); ++$i) {
		$temp_table_column = $temp_table_template[$i];

		$column_name = get_column_name($i);

    if (!isset($temp_table_row[$temp_table_column['field']])) {
      continue;
    }

		$sheet->setCellValue($column_name.($row), $temp_table_row[$temp_table_column['field']]);

		if (is_cell_incorrect($temp_table_row, $temp_table_column)) {
			$incorrect_count++;

			$sheet
			->getStyle($column_name.($row))->getFill()
	    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	    ->getStartColor()->setARGB('FFFFCCCC');
		}
	}

	$row += 1;
}

/* СОЗДАНИЕ И ВЫГРУЗКА ФАЙЛА */
$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/json');
ob_start();

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$xlsData = ob_get_contents();
ob_end_clean();


$query = "SELECT * from sys_logging_main_forestries where tmp_tab_id='{$temp_table_id}'";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$temp_table_info = pg_fetch_assoc($result);

if ($temp_table_info) {
	$temp_table_file_name = $temp_table_info['file_name'];

	// Вырезаем расширение, если оно есть
	$temp_table_file_name_arr = explode('.', $temp_table_file_name);
	if ($temp_table_file_name_arr[count($temp_table_file_name_arr) - 1] === 'csv') {
		array_splice($temp_table_file_name_arr, count($temp_table_file_name_arr) - 1, 1);
		// $temp_table_file_name_arr[] = 'csv';
	}

	// array_splice($temp_table_file_name_arr, count($temp_table_file_name_arr) - 1, 0, ' - errors');

	$temp_table_file_name_arr[count($temp_table_file_name_arr) - 1] = $temp_table_file_name_arr[count($temp_table_file_name_arr) - 1] . ' - errors';

	$temp_table_file_name = implode('.', $temp_table_file_name_arr);
}

$file_name = ($temp_table_file_name ? $temp_table_file_name : 'errors') . '.xlsx';
// $file_name = 'errors';
$file_content = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'.base64_encode($xlsData);

$response = array(
  'name' => $file_name,
  'file' => $file_content
);

echo json_encode($response);
pg_close($dbconn);