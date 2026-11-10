<?php
// public/editprofile.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php'; // <-- must provide $conn
$email = $_SESSION['email'];

// fetch current user
$stmt = $conn->prepare("SELECT name, age, gender FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed (select): " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

if (!$data) {
    echo "User not found.";
    exit;
}

// handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);

    $upt = $conn->prepare("UPDATE users SET name = ?, age = ?, gender = ? WHERE email = ?");
    if (!$upt) {
        die("Prepare failed (update): " . $conn->error);
    }
    $upt->bind_param("ssss", $name, $age, $gender, $email);
    if ($upt->execute()) {
        echo "<script>alert('Profile updated'); window.location='profile.php';</script>";
        exit;
    } else {
        echo "Update failed: " . $upt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Profile</title>
  <style>
    body{ font-family:arial; background:#0b1726; color:#fff; text-align:center; padding-top:30px; }
    .box{ width:420px; margin:auto; background:rgba(255,255,255,0.06); padding:22px; border-radius:12px; }
    input, select { width:92%; padding:10px; margin:8px 0; border-radius:8px; border:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.03); color:#fff; }
    .btn{ width:95%; padding:12px; background:#7dd3fc; color:#052936; border:none; border-radius:8px; font-weight:bold; cursor:pointer; }
  </style>
</head>
<body>
  <h2>Edit Profile ✏️</h2>
  <div class="box">
    <form method="post">
      <input name="name" value="<?= htmlspecialchars($data['name']) ?>" required placeholder="Full name">
      <input name="age" value="<?= htmlspecialchars($data['age']) ?>" placeholder="Age (e.g. 25)">
      <select name="gender" required>
        <option value="">Select gender</option>
        <option value="Male" <?= ($data['gender']=='Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= ($data['gender']=='Female') ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= ($data['gender']=='Other') ? 'selected' : '' ?>>Other</option>
      </select>

      <button class="btn" type="submit">Update Profile</button>
    </form>
    <div style="margin-top:10px;"><a href="profile.php" style="color:#7dd3fc">⬅ Back to Profile</a></div>
  </div>
</body>
</html>
