<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['user'])) exit;

$email = $_SESSION['email'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sleep_history.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Date','Sleep Hours','Sleep Quality','Mood','AI Score']);

$query = $conn->prepare("SELECT * FROM sleepdata WHERE user_email=? ORDER BY created_at ASC");
$query->bind_param("s",$email);
$query->execute();
$res = $query->get_result();

while($row = $res->fetch_assoc()){
    fputcsv($output, [$row['created_at'], $row['sleep_hours'], $row['sleep_quality'], $row['mood'], $row['ai_score']]);
}

fclose($output);
exit;
