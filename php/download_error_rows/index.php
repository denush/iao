<?php

ini_set("memory_limit", "1500M");

require_once '../connect.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$spreadsheet->getDefaultStyle()->applyFromArray([
	'font' => [
    'size' => 12,
    'name' => 'Times New Roman'
  ]
]);

$sheet = $spreadsheet->getActiveSheet();

/* ПОДКЛЮЧЕНИЕ СВОИХ ВСПОМОГАТЕЛЬНЫХ ФУНКЦИЙ И КЛАССОВ */
// require_once 'auxiliary_functions.php';

// require_once 'PhpExcelStyler.php';
// $setStyle = new PhpExcelStyler($sheet);

// require_once 'DataStore.php';
// $dataStore = new DataStore($dbconn);

// /* ФОРМИРОВАНИЕ ЛИСТОВ АКТА */
// require_once 'sheets/title.php';
// require_once 'sheets/visual.php';
// require_once 'sheets/instrum.php';
// require_once 'sheets/annex_1.php';
// require_once 'sheets/annex_1_1.php';
// require_once 'sheets/annex_2/index.php';
// require_once 'sheets/annex_3.php';

$temp_table_id = $_POST['table_id'];

// Получение шаблона временной таблицы
$query = "SELECT * from sys_fields_list  order by field_num";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$temp_table_template = [];
while ($row = pg_fetch_assoc($result)) {
	$temp_table_template[] = $row;
}

// Получение некорректных строк временной таблицы

$incorrect_rows_filter = "(is_row_correct=''false'') and (is_row_checked=''true'')";
$query = "SELECT * from czl_get_tmp_table('{$temp_table_id}', null, null, '{$incorrect_rows_filter}')";
$result = pg_query($dbconn, $query) or die('Ошибка запроса: ' . pg_last_error());

$temp_table = [];
while ($row = pg_fetch_assoc($result)) {
	$temp_table[] = $row;
}

// echo json_encode($temp_table_template, JSON_UNESCAPED_UNICODE);
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

			// echo json_encode($temp_table_row[$temp_table_column['field_check']], JSON_UNESCAPED_UNICODE);
			// exit;

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
// 		echo json_encode($temp_table, JSON_UNESCAPED_UNICODE);
// exit;


		$temp_table_column = $temp_table_template[$i];

		$column_name = get_column_name($i);

		$sheet->setCellValue($column_name.($row), $temp_table_row[$temp_table_column['field']]);

		if (is_cell_incorrect($temp_table_row, $temp_table_column)) {
			$incorrect_count++;

			$sheet->getStyle($column_name.($row))->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFF9966');




		}
	}

	$row += 1;
}

		// echo json_encode($incorrect_count, JSON_UNESCAPED_UNICODE);
// exit;

/* СОЗДАНИЕ И ВЫГРУЗКА ФАЙЛА */
$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/json');

ob_start();

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$xlsData = ob_get_contents();
ob_end_clean();

// $forestry = $dataStore->lpo_group[0]['main_lpo_info']['forestry'];
// $localforestry = $dataStore->lpo_group[0]['main_lpo_info']['localforestry'];
// $subforestry = $dataStore->lpo_group[0]['main_lpo_info']['subforestry'];
// $area = $dataStore->lpo_group[0]['main_lpo_info']['area'];

// $file_name = 'Акт_'.$forestry.'_'.$localforestry.'_'.$subforestry.'_'.$area . '.xlsx';

$file_name = 'errors';
$file_content = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'.base64_encode($xlsData);

$response = array(
  'name' => $file_name,
  'file' => $file_content
);

echo json_encode($response);
pg_close($dbconn);