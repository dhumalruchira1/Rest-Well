<?php
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_GET['id'])) {
    die("Invalid booking!");
}

$id = $_GET['id'];

$sql = "SELECT * FROM bookings WHERE id='$id'";
$res = $conn->query($sql);

if ($res->num_rows === 0) {
    die("Booking not found!");
}

$data = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Confirmed</title>

<style>
body {
    background:#071521;
    color:white;
    font-family: 'Poppins', Arial, sans-serif;
    text-align:center;
    padding-top:60px;
}

/* Main success box */
.box {
    width:90%;
    max-width:500px;
    margin:auto;
    background:rgba(255,255,255,0.10);
    padding:35px;
    border-radius:18px;
    box-shadow:0 0 30px rgba(0,255,255,0.15);
    backdrop-filter:blur(6px);
    animation: fadeIn 0.8s ease-in-out;
}

/* Success tick animation */
.tick {
    font-size:60px;
    color:#7dd3fc;
    animation: bounce 1.3s infinite;
}

/* Heading */
h2 {
    margin-top:15px;
    font-size:26px;
    color:#a7e8ff;
    letter-spacing:1px;
}

/* Buttons */
.btn {
    display:block;
    margin:18px auto;
    padding:14px 20px;
    border-radius:10px;
    text-decoration:none;
    color:#052b36;
    font-weight:bold;
    font-size:16px;
    width:80%;
    max-width:300px;
    transition:0.25s;
}

.download-btn { 
    background:#7dd3fc; 
}
.download-btn:hover {
    transform:scale(1.06);
    background:#9fe7ff;
}

.cancel-btn { 
    background:#ff9f9f; 
}
.cancel-btn:hover {
    transform:scale(1.06);
    background:#ffc4c4;
}

/* Sub text */
.details-text {
    font-size:15px;
    margin-top:10px;
    color:#d4f4ff;
}

/* Animations */
@keyframes fadeIn {
    from { opacity:0; transform:translateY(25px); }
    to   { opacity:1; transform:translateY(0); }
}

@keyframes bounce {
    0%,100% { transform:translateY(0); }
    50%     { transform:translateY(-8px); }
}
</style>
</head>

<body>

<div class="box">
    <div class="tick">✔</div>

    <h2>Your Booking is Confirmed 🎉</h2>

    <p class="details-text">
        Booking ID: <strong>#<?php echo $id; ?></strong><br>
        Expert: <strong><?php echo $data['expert_name']; ?></strong>
    </p>

    <a class="btn download-btn" href="pdf_generate.php?id=<?php echo $id; ?>">
        Download Your Booking PDF
    </a>

    <a class="btn cancel-btn" href="cancel_booking.php?id=<?php echo $id; ?>">
        Cancel Booking
    </a>
</div>

</body>
</html>
