<?php
require('fpdf/fpdf.php');
include "config/koneksi.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'Laporan Pembayaran',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'ID',1);
$pdf->Cell(40,10,'User',1);
$pdf->Cell(50,10,'Judul Buku',1);
$pdf->Cell(30,10,'Harga',1);
$pdf->Cell(30,10,'Metode',1);
$pdf->Cell(30,10,'Tanggal',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
$query = "
    SELECT p.id, u.username, b.judul, p.total_harga, p.metode_pembayaran, p.tanggal_pembayaran
    FROM pembayaran p
    JOIN users u ON p.user_id = u.id
    JOIN buku b ON p.buku_id = b.id
    ORDER BY p.id DESC
";
$result = mysqli_query($koneksi, $query);

while($row = mysqli_fetch_assoc($result)){
    $pdf->Cell(10,10,$row['id'],1);
    $pdf->Cell(40,10,$row['username'],1);
    $pdf->Cell(50,10,$row['judul'],1);
    $pdf->Cell(30,10,'Rp '.number_format($row['total_harga'],0,',','.'),1);
    $pdf->Cell(30,10,$row['metode_pembayaran'],1);
    $pdf->Cell(30,10,$row['tanggal_pembayaran'],1);
    $pdf->Ln();
}

$pdf->Output();
?>