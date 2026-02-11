<?php
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Headers
$headers = [
    'Brand', 
    'Category', 
    'Sub Category', 
    'Title', 
    'SKU', 
    'HSN Code', 
    'Description', 
    'Specifications',
    'MRP', 
    'Sales Price', 
    'Stock', 
    'Is Price Request (1/0)',
    'Meta Title', 
    'Meta Description', 
    'Meta Keywords'
];

$sheet->fromArray($headers, NULL, 'A1');

// Style the header row
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFE0E0E0'],
    ],
];
$sheet->getStyle('A1:O1')->applyFromArray($headerStyle);

// Auto-size columns
foreach (range('A', 'O') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Add a sample row (optional, for guidance)
$sampleData = [
    'Brand Name', 'Category Name', 'Sub Category Name', 'Sample Product Title', 'SKU-001', '123456', 'Product Description', 'Specs...', '1000', '900', '50', '0', 'Meta Title', 'Meta Desc', 'keyword1, keyword2'
];
$sheet->fromArray($sampleData, NULL, 'A2');

// Set content type and filename
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="product_import_sample.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
