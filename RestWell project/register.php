<?php
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php'; // Database connection
require_once 'vender/twilio-php-main/src/Twilio/autoload.php'; // Twilio library path

use Twilio\Rest\Client;

session_start();

if (isset($_POST['submit'])) {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $password = password_hash($password_raw, PASSWORD_DEFAULT);
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Prepare and insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, age, gender, phone) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssssss", $name, $email, $password, $age, $gender, $phone);

    if ($stmt->execute()) {

         // ---------------- Twilio SMS CODE ---------------- //
        $sid = getEnvValue('TWILIO_ACCOUNT_SID', '');          // Twilio Account SID
        $token = getEnvValue('TWILIO_AUTH_TOKEN', '');         // Twilio Auth Token
        $twilioNumber = getEnvValue('TWILIO_PHONE_NUMBER', ''); // Twilio number you claimed
        $toPhone = $phone;                       // User's phone number (+country code)

        if (!empty($sid) && !empty($token) && !empty($twilioNumber)) {
            $client = new Client($sid, $token);

            try {
                $message = $client->messages->create(
                    $toPhone,
                    [
                        "from" => $twilioNumber,
                        "body" => "Hello $name, your registration on RestWell is successful!"
                    ]
                );

                $sms_status = "SMS Sent Successfully! Message SID: " . $message->sid;

            } catch (Exception $e) {
                error_log("Twilio SMS Error: " . $e->getMessage());
                $sms_status = "SMS Failed!";
            }
        } else {
            $sms_status = "Twilio credentials not configured.";
        }
        // -------------------------------------------- //

        $_SESSION['email'] = $email;

        echo "<script>
                alert('Registration Successful! $sms_status');
                window.location.href='dashboard.php';
              </script>";
        exit;

    } else {
        echo "<script>alert('Registration Failed. Try again!');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — RestWell</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { margin:0; font-family:Arial,sans-serif; background:#0b1726; color:#fff; }
    .night-bg { position:fixed; inset:0; background:linear-gradient(#0b1726,#081018); z-index:-1; overflow:hidden; }
    .stars { width:100%; height:100%; background:transparent url('https://www.transparenttextures.com/patterns/stardust.png') repeat; animation: moveStars 100s linear infinite; }
    @keyframes moveStars { from{background-position:0 0;} to{background-position:-10000px 5000px;} }
    .moon { width:100px; height:100px; background:radial-gradient(circle,#fff 60%,#ccc 100%); border-radius:50%; position:absolute; top:60px; right:80px; animation: floatMoon 6s ease-in-out infinite alternate; }
    @keyframes floatMoon { from{transform:translateY(0);} to{transform:translateY(20px);} }
    header { padding:20px; display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.4); position:sticky; top:0; }
    header h2 { color:#7dd3fc; margin:0; }
    nav a { margin:0 12px; color:#9fd8ff; text-decoration:none; font-weight:700; }
    .center-page { min-height:100vh; display:flex; justify-content:center; align-items:center; padding-top:60px; }
    .form-card { background: rgba(255,255,255,0.05); padding:25px; border-radius:12px; width:360px; box-shadow:0 8px 24px rgba(0,0,0,0.35); text-align:left; }
    .form-card h2 { text-align:center; margin-bottom:20px; color:#7dd3fc; }
    input, select { width:100%; padding:10px; margin:8px 0; border-radius:6px; border:1px solid rgba(255,255,255,0.1); background:transparent; color:#fff; }
    .btn-submit { width:100%; background: linear-gradient(90deg,#73b3ff,#7dd3fc); color:#052936; padding:10px; border:0; border-radius:8px; font-weight:700; cursor:pointer; margin-top:10px; }
</style>
</head>
<body>

<div class="night-bg">
  <div class="stars"></div>
  <div class="moon"></div>
</div>

<header>
    <h2>🌙 RestWell</h2>
    <nav>
        <a href="index.php">Home</a>
        <a href="exercise.php">Exercises</a>
        <a href="health.php">Health Book</a>
    </nav>
</header>

<div class="center-page">
  <div class="form-card">
    <h2>Create Account</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="age" required>
            <option value="">Select Age</option>
            <option value="10-20">10-20</option>
            <option value="21-30">21-30</option>
            <option value="31-40">31-40</option>
            <option value="40+">40+</option>
        </select>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="phone" placeholder="Phone Number (with +91)" required>
        <button type="submit" name="submit" class="btn-submit">Register</button>
    </form>

    <p style="text-align:center;margin-top:10px;color:#cfefff;">
      Already have an account? <a href="login.php" style="color:#7dd3fc;">Login</a>
    </p>
  </div>
</div>

</body>
</html>
