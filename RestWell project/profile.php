<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

$query = $conn->prepare("SELECT name, email, age, gender, created_at FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();

$query->bind_result($name, $email, $age, $gender, $created_at);
$query->fetch();
?>

<!DOCTYPE html>
<html>
<head>
<title>User Profile — RestWell</title>

<style>
body {
    margin: 0;
    background: #0b1726;
    font-family: Arial;
    color: white;
}

.container {
    width: 450px;
    margin: 80px auto;
    padding: 25px;
    background: rgba(255,255,255,0.08);
    border-radius: 14px;
    box-shadow: 0 0 25px rgba(255,255,255,0.18);
    text-align: center;
    backdrop-filter: blur(7px);
}

h2 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #7dd3fc;
}

.profile-info {
    text-align: left;
    margin-top: 15px;
    font-size: 17px;
    line-height: 30px;
}

.btn {
    display: block;
    width: 90%;
    margin: 15px auto;
    padding: 12px;
    background: linear-gradient(90deg,#73b3ff,#7dd3fc);
    border-radius: 8px;
    text-decoration: none;
    color: #052936;
    font-weight: bold;
}

.back {
    color: #a8d8ff;
    text-decoration: none;
    display: block;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="container">
    <h2>👤 User Profile</h2>

    <div class="profile-info">
        <b>Name:</b> <?php echo $name; ?><br>
        <b>Email:</b> <?php echo $email; ?><br>
        <b>Age:</b> <?php echo $age; ?><br>
        <b>Gender:</b> <?php echo $gender; ?><br>
        <b>Member Since:</b> <?php echo $created_at; ?>
    </div>

    <a class="btn" href="editprofile.php">✏ Edit Profile</a>
    <a class="back" href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
