<?php
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';
require("fpdf/fpdf.php");

if (!isset($_GET['id'])) {
    die("Invalid PDF request!");
}

$id = $_GET['id'];
$sql = "SELECT * FROM bookings WHERE id='$id'";
$res = $conn->query($sql);

if ($res->num_rows === 0) {
    die("Booking not found!");
}

$data = $res->fetch_assoc();


class PDF extends FPDF {

    function Header() {
        $this->SetFillColor(30, 144, 255);
        $this->Rect(0,0,210,30,'F');

        $this->SetTextColor(255,255,255);
        $this->SetFont('Arial','B',20);
        $this->Cell(0,15,'Restwell - Booking Receipt',0,1,'C');

        $this->Ln(5);
        $this->SetTextColor(0,0,0);
    }

    function SectionTitle($title) {
        $this->SetFont('Arial','B',14);
        $this->SetFillColor(220,220,220);
        $this->Cell(0,10,$title,0,1,'L',true);
        $this->Ln(2);
    }

    function Field($label,$value) {
        $this->SetFont('Arial','',12);
        $this->Cell(60,10,$label,1,0,'L');
        $this->Cell(0,10,$value,1,1,'L');
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Thank you for choosing Restwell!',0,1,'C');
    }
}


$pdf = new PDF();
$pdf->AddPage();
$pdf->Ln(10);

$pdf->SectionTitle("Personal & Booking Details");

$pdf->Field("Full Name", $data['user_name']);
$pdf->Field("Email", $data['email']);
$pdf->Field("Expert", $data['expert_name']);
$pdf->Field("Meeting Type", $data['meeting_type']);
$pdf->Field("Date", $data['date']);
$pdf->Field("Time", $data['time']);

if ($data['meeting_type'] == "Online Meeting") {
    $pdf->SectionTitle("Online Meeting Details");
    $pdf->MultiCell(0,10,"Zoom Meeting Link:\n".$data['meeting_link'],1);
}

if ($data['meeting_type'] == "Clinic Visit") {
    $pdf->SectionTitle("Selected Clinic");
    $pdf->MultiCell(0,10,$data['location'],1);
}

$pdf->SectionTitle("Additional Notes");
$pdf->MultiCell(0,10,$data['additional_info'],1);

$pdf->Output("I","Booking_$id.pdf");
