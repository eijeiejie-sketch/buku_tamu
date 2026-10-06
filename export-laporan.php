<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit;
}

include('koneksi.php');
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->setCellValue('A1', 'Laporan Buku Tamu');
$sheet->setCellValue('A2', 'Laporan Hari Ini');
$sheet->setCellValue('A4', 'No');
$sheet->setCellValue('B4', 'TANGGAL');
$sheet->setCellValue('C4', 'NAMA TAMU');
$sheet->setCellValue('D4', 'ALAMAT');
$sheet->setCellValue('E4', 'NO TELEPON/HP');
$sheet->setCellValue('F4', 'BERTEMU DENGAN');
$sheet->setCellValue('G4', 'KEPENTINGAN');

// buat header kolom bold
$sheet->getStyle('A1:G1')->getFont()->setBold(true);

if (isset($_GET['cari'])) {
    $p_awal  = $_GET['p_awal'];
    $p_akhir = $_GET['p_akhir'];
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir' ORDER BY tanggal ASC");
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu ORDER BY tanggal ASC");
}

$i  = 5;
$no = 1;
while ($d = mysqli_fetch_array($data)) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $d['tanggal']);
    $sheet->setCellValue('C' . $i, $d['nama_tamu']);
    $sheet->setCellValue('D' . $i, $d['alamat']);
    $sheet->setCellValue('E' . $i, $d['no_hp']);
    $sheet->setCellValue('F' . $i, $d['bertemu']);
    $sheet->setCellValue('G' . $i, $d['kepentingan']);
    $i++;
}

// lebarkan kolom otomatis
foreach (range('A', 'G') as $kolom) {
    $sheet->getColumnDimension($kolom)->setAutoSize(true);
}

$sheet->setTitle('Laporan Buku Tamu');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Buku Tamu.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
