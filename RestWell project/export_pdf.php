<?php
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';
session_start();

if (!isset($_SESSION['email'])) {
    echo "Not logged in!";
    exit;
}

$email = $_SESSION['email'];

$query = $conn->prepare("SELECT created_at, sleep_hours, sleep_quality, mood, ai_score FROM sleepdata WHERE user_email=? ORDER BY created_at ASC");
$query->bind_param("s", $email);
$query->execute();
$res = $query->get_result();

require("fpdf/fpdf.php");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,10,"Sleep History Report",0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,"Date",1);
$pdf->Cell(30,10,"Hours",1);
$pdf->Cell(30,10,"Quality",1);
$pdf->Cell(40,10,"Mood",1);
$pdf->Cell(40,10,"AI Score",1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);

while($row = $res->fetch_assoc()){
    $pdf->Cell(40,10,$row['created_at'],1);
    $pdf->Cell(30,10,$row['sleep_hours'],1);
    $pdf->Cell(30,10,$row['sleep_quality'],1);
    $pdf->Cell(40,10,$row['mood'],1);
    $pdf->Cell(40,10,$row['ai_score'],1);
    $pdf->Ln();
}

$pdf->Output("D","Sleep_Report.pdf");
?>
