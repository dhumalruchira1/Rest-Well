<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1){
        
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){

            // SESSION SET HERE 
            $_SESSION['email'] = $row['email'];
            $_SESSION['user']  = $row['name'];

            echo "<script>
                    alert('Login Successful!');
                    window.location.href='dashboard.php';
                  </script>";
            exit;

        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — RestWell</title>

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

    body {
      margin: 0;
      background: #0b1726;
      font-family: Arial, sans-serif;
      color: white;
      overflow-x: hidden;
    }
    body { margin:0; font-family:Arial,sans-serif; background:#0b1726; color:#fff; }
header { padding:20px; display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.4); position:sticky; top:0; z-index:10; }
header h2 { margin:0; color:#7dd3fc; }
nav a { margin:0 12px; text-decoration:none; color:#9fd8ff; font-weight:bold; }
nav a:hover {color:#fff;}
.section-title { margin-top:40px; font-size:28px; text-align:center; font-weight:bold; }
.section-sub { text-align:center; color:#9fd8ff; margin-bottom:30px; font-size:16px; }


    /* Background full container */
    .night-bg {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: -1;
      overflow: hidden;
      background: linear-gradient(#0b1726, #07101c);
    }

    /* Stars */
    .stars {
      width: 100%; height: 100%;
      background: url('https://www.transparenttextures.com/patterns/stardust.png') repeat;
      animation: starsMove 120s linear infinite;
      opacity: 0.6;
    }

    @keyframes starsMove {
      from { background-position: 0 0; }
      to { background-position: -10000px 5000px; }
    }

    /* Moon */
    .moon {
      width: 110px;
      height: 110px;
      background: radial-gradient(circle, #fff 60%, #ccc 100%);
      border-radius: 50%;
      position: absolute;
      top: 60px;
      right: 70px;
      box-shadow: 0 0 25px rgba(255,255,255,0.6);
      animation: floatMoon 5s ease-in-out infinite alternate;
    }

    @keyframes floatMoon {
      from { transform: translateY(0px); }
      to { transform: translateY(20px); }
    }

    /* CENTERING LOGIN BOX */
    .center {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 60px;
    }

    /* Login card */
    .login-box {
        width: 380px;
        padding: 28px;
        background: rgba(255,255,255,0.08);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        backdrop-filter: blur(6px);
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 15px;
        color: #7dd3fc;
    }

    /* Inputs */
    input {
        width: 100%;
        padding: 12px;
        margin: 12px 0;
        border-radius: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
    }

    input:focus {
        outline: none;
        border-color: #7dd3fc;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(90deg,#73b3ff,#7dd3fc);
        font-weight: bold;
        color: #052936;
        cursor: pointer;
    }

</style>
</head>

<body>

<!-- BACKGROUND -->
<div class="night-bg">
    <div class="stars"></div>
    <div class="moon"></div>
</div>

<!-- Background -->
<div class="night-bg">
  <div class="stars"></div>
  <div class="moon"></div>
</div>

<!-- NAVBAR -->
<header>
  <h2>🌙 Restwell</h2>
  <nav>
    <a href="index.php">Home</a>
    <a href="exercise.php">Exercises</a>
    <a href="book.php">Health Books</a>

    <?php if(isset($_SESSION['user'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php" style="color:#ff9f9f;">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>

<!-- CENTER CONTENT -->
<div class="center">
    <div class="login-box">
        

        <h2>Login</h2>

        <form method="POST" autocomplete="on">

            <input type="email" name="email" placeholder="Email" required autocomplete="username">

            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">

            <button type="submit" name="login" class="btn-submit">Login</button>
        </form>

        <p style="text-align:center;margin-top:12px;">
            Don't have an account? 
            <a href="register.php" style="color:#7dd3fc;">Register</a>
        </p>

    </div>
</div>

</body>
</html>
