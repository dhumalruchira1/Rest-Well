<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : 'src/db.php';

if(!isset($_SESSION['otp'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['verify'])) {
    $entered_otp = $_POST['otp'];

    if(time() - $_SESSION['otp_time'] > 300){
        unset($_SESSION['otp']);
        unset($_SESSION['otp_phone']);
        unset($_SESSION['otp_time']);
        echo "<script>alert('OTP expired. Please login again.'); window.location='login.php';</script>";
        exit;
    }

    if($entered_otp == $_SESSION['otp']){
        $phone = $_SESSION['otp_phone'];
        $query = $conn->prepare("SELECT * FROM users WHERE phone=? LIMIT 1");
        $query->bind_param("s",$phone);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        $_SESSION['user'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['age'] = $user['age'];
        $_SESSION['gender'] = $user['gender'];

        unset($_SESSION['otp']);
        unset($_SESSION['otp_phone']);
        unset($_SESSION['otp_time']);

        echo "<script>alert('Login Successful!'); window.location='dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify OTP — RestWell (Debug)</title>
<style>
body{margin:0;font-family:Arial,sans-serif;background:#0b1726;color:#fff;}
.otp-box{width:400px;margin:140px auto;background:rgba(255,255,255,0.08); padding:30px; border-radius:14px; backdrop-filter:blur(6px); box-shadow:0 8px 25px rgba(0,0,0,0.45); text-align:center;}
.otp-box h2{font-size:26px;margin-bottom:10px;}
.otp-box p{color:#9fd8ff;margin-bottom:20px;}
.otp-box input{width:100%; padding:12px; margin-bottom:18px; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:transparent; color:white;}
.btn-verify{width:100%; background:linear-gradient(90deg,#73b3ff,#7dd3fc); padding:12px; border:none; border-radius:8px; font-weight:bold; color:#052936; cursor:pointer; margin-top:8px;}
</style>
</head>
<body>
<div class="otp-box">
<h2>Verify OTP</h2>
<p>Enter the OTP sent to your phone number.</p>
<form method="POST">
<input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
<button type="submit" name="verify" class="btn-verify">Verify</button>
</form>
</div>
</body>
</html>
