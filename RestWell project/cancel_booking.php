<?php
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_GET['id'])) {
    die("Invalid request!");
}

$id = $_GET['id'];

// Check booking exists
$sql = "SELECT * FROM bookings WHERE id='$id'";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    die("<h2 style='color:red; text-align:center;'>Booking not found!</h2>");
}

$data = $res->fetch_assoc();

// If already cancelled
if ($data['status'] === "cancelled") {
    echo "<script>
            alert('Booking already cancelled!');
            window.location='dashboard.php';
          </script>";
    exit;
}

// Cancel booking
$time = date("Y-m-d H:i:s");

$update = "UPDATE bookings 
           SET status='cancelled', cancelled_at='$time'
           WHERE id='$id'";

if ($conn->query($update)) {
    // ✅ Show separate styled cancel page
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Booking Cancelled</title>
        <style>
           /* Body & background */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #1e1e2f;  /* dark background */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Card container */
.card {
    background: #2c2c3a;  /* dark card */
    padding: 50px 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    text-align: center;
    max-width: 400px;
    color: #fff;
    animation: fadeIn 0.8s ease-in-out;
}

/* Fade-in animation */
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(-30px);}
    100% {opacity:1; transform: translateY(0);}
}

/* Heading */
.card h1 {
    font-size: 32px;
    color: #ff4d6d;  /* pink/red heading */
    margin-bottom: 20px;
}

/* Paragraph */
.card p {
    font-size: 17px;
    color: #cfcfcf;
    margin-bottom: 30px;
}

/* Button */
.btn {
    display: inline-block;
    padding: 14px 35px;
    background: linear-gradient(90deg, #4e89ff, #1c6ef2);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    transition: 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

/* Button hover effect */
.btn:hover {
    background: linear-gradient(90deg, #1c6ef2, #4e89ff);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}

        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Booking Cancelled!</h1>
            <p>Your booking has been successfully cancelled.</p>
            <a class='btn' href='dashboard.php'>Go to Dashboard</a>
        </div>
    </body>
    </html>";
} else {
    echo "<h2 style='color:red; text-align:center;'>DB Error: " . $conn->error . "</h2>";
}
?>
