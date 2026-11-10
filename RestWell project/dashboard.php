<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$user = $_SESSION['user'];
$email = $_SESSION['email'];

// Default values
$last_sleep = "No sleep data yet";
$last_booking = "No booking yet";
$payment_done = "No payments yet";

/* ---------------- FETCH LAST SLEEP RECORD ---------------- */
$sleep = $conn->prepare("SELECT sleep_hours, sleep_quality FROM sleepdata 
                         WHERE user_email=? ORDER BY id DESC LIMIT 1");
$sleep->bind_param("s", $email);
$sleep->execute();
$sleepResult = $sleep->get_result()->fetch_assoc();
$last_sleep = $sleepResult 
    ? $sleepResult['sleep_hours'] . " hrs, " . $sleepResult['sleep_quality'] 
    : "No sleep data yet";

/* ---------------- FETCH LAST CONSULT BOOKING ---------------- */
$book = $conn->prepare("SELECT expert_name, date, time FROM consult 
                        WHERE email=? ORDER BY id DESC LIMIT 1");
$book->bind_param("s", $email);
$book->execute();
$booking = $book->get_result()->fetch_assoc();
$last_booking = $booking 
    ? $booking['expert_name'] . " on " . $booking['date'] . " at " . $booking['time']
    : "No booking yet";


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RestWell — Dashboard</title>

<style>
    /* ===== Common Smooth UI Styles ===== */

/* Body & Page Smooth Transition */
body {
    transition: background 0.5s ease, color 0.5s ease, transform 0.3s ease;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Links & Buttons */
a, button {
    cursor: pointer;
    transition: all 0.3s ease;
    outline: none;
    text-decoration: none;
    user-select: none;
}

/* Button Hover & Active Effects */
button {
    background-color: #1e88e5;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transform: translateY(0);
}

button:hover {
    background-color: #42a5f5;
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

button:active {
    transform: translateY(1px) scale(0.98);
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

/* Input & Select Smooth Focus */
input, select, textarea {
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
    border-color: #1e88e5;
    box-shadow: 0 0 8px rgba(30,136,229,0.3);
    outline: none;
}

/* Card / Box Hover Smooth */
.card, .glass, .feature-box {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover, .glass:hover, .feature-box:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0,0,0,0.4);
}

/* Smooth Tab / Section Transitions */
.tips-section, .tab-content {
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.tips-section.hide {
    opacity: 0;
    transform: translateY(20px);
    pointer-events: none;
}

.tips-section.show {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
}

/* Optional Page Fade Transition */
.page-transition {
    transition: opacity 0.5s ease;
}

.page-transition.fade-out {
    opacity: 0;
}

.page-transition.fade-in {
    opacity: 1;
}

/* ---- YOUR ORIGINAL CSS (UNCHANGED) ---- */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0b1726;
    color: #fff;
}
.night-bg {
    position: fixed;
    inset: 0;
    background: linear-gradient(#0b1726, #09101b);
    z-index: -1;
}
.home-btn {
    position: fixed;
    top: 18px;
    left: 20px;
    font-size: 22px;
    text-decoration: none;
    color: #7dd3fc;
    padding: 10px 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(5px);
    box-shadow: 0 0 10px rgba(125,211,252,0.5);
    transition: 0.3s;
}
.home-btn:hover {
    transform: scale(1.15);
    background: rgba(125,211,252,0.3);
}
.profile-card {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 60px;
    padding: 10px;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    text-align: center;
    backdrop-filter: blur(12px);
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.2);
    z-index: 10;
    transition: width 0.3s ease;
    overflow: hidden;
}
.profile-card:hover {
    width: 200px;
}
.profile-card img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid #7dd3fc;
    background: white;
    margin-bottom: 5px;
}
.profile-card h3 {
    margin: 5px 0;
    font-size: 16px;
    color: #7dd3fc;
}
.profile-card p {
    font-size: 12px;
    opacity: 0.9;
    margin: 2px 0;
}
.profile-btns {
    margin-top: 5px;
}
.profile-btns a {
    display: block;
    margin: 5px 0;
    padding: 5px 8px;
    font-size: 12px;
    border-radius: 6px;
    font-weight: bold;
    text-decoration: none;
    background: linear-gradient(90deg,#73b3ff,#7dd3fc);
    color: #052936;
}
.profile-btns a.btn-edit {
    background: #ffb4d6;
    color: #460019;
}
header {
    text-align: center;
    font-size: 28px;
    padding: 20px;
    color: #7dd3fc;
    margin-top: 40px;
    text-shadow: 0 0 10px rgba(125,211,252,0.5);
}
.welcome {
    text-align: center;
    font-size: 26px;
    margin-bottom: 20px;
}
.card-container {
    width: 90%;
    max-width: 1100px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
}
.card {
    width: 260px;
    background: rgba(255,255,255,0.08);
    padding: 25px;
    border-radius: 14px;
    text-align: center;
    backdrop-filter: blur(8px);
    transition: 0.3s;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}
.card:hover {
    transform: scale(1.08);
    box-shadow: 0 0 20px #7dd3fc;
}
.card-title {
    font-size: 20px;
    margin-bottom: 10px;
    color: #7dd3fc;
}
.info {
    opacity: 0.9;
    font-size: 14px;
    margin-bottom: 10px;
}
.card a {
    display: inline-block;
    margin-top: 8px;
    padding: 10px 14px;
    border-radius: 8px;
    background: linear-gradient(90deg,#73b3ff,#7dd3fc);
    color: #052936;
    text-decoration: none;
    font-weight: bold;
}
.logout-btn {
    display: block;
    width: 200px;
    margin: 40px auto;
    text-align: center;
    padding: 12px;
    background: #ff4d6d;
    color: white;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}
.logout-btn:hover {
    background: #ff2b4f;
    transform: scale(1.05);
}
</style>
</head>

<body>
<div class="night-bg"></div>

<a href="index.php" class="home-btn">🏠</a>

<!-- Floating Profile Card -->
<div class="profile-card">
    <img src="https://cdn-icons-png.flaticon.com/512/9131/9131529.png" alt="Profile">
    <h3><?php echo $user; ?></h3>
    <p><?php echo $email; ?></p>

    <div class="profile-btns">
        <a href="profile.php">View Profile</a>
        <a href="editprofile.php" class="btn-edit">Edit Profile</a>
        <a href="logout.php">Logout</a>
        <a href="delete_account.php" style="background:#ff4d6d;color:white;">Delete Account</a>
    </div>
</div>

<header>🌙 RestWell Dashboard</header>
<div class="welcome">Welcome, <b><?php echo $user; ?></b> 👋</div>

<div class="card-container">
    <div class="card">
        <div class="card-title">Sleep Data</div>
        <div class="info"><?php echo $last_sleep; ?></div>
        <a href="sleepdata.php">Open</a>
    </div>

    <div class="card">
        <div class="card-title">Sleep History</div>
        <a href="sleephistory.php">View All</a>
    </div>

    <div class="card">
        <div class="card-title">Book Consultant</div>
        <div class="info"><?php echo $last_booking; ?></div>
        <a href="consult.php">Book</a>
    </div>

    <div class="card">
        <div class="card-title">Payments</div>
        <div class="info"><?php echo $payment_done; ?></div>
        <a href="payments.php">View</a>
    </div>

    <div class="card">
        <div class="card-title">Sleep Tips</div>
        <a href="sleep_tips.php">View</a>
    </div>

    <div class="card">
        <div class="card-title">Exercises</div>
        <a href="exercise.php">Start</a>
    </div>

    <div class="card">
        <div class="card-title">Feedback</div>
        <a href="feedback.php">Submit</a>
    </div>

<div class="card">
    <div class="card-title">Health Book</div>
    <a href="book.php">Buy Now</a>
</div>

</body>
</html>
