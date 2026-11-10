<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT * FROM consult WHERE email=? ORDER BY id DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>My Consultations</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h2>Your Consultations</h2>

<?php while ($row = $result->fetch_assoc()): ?>
<div class="card">
    <h3><?php echo $row['expert_name']; ?></h3>

    <p><b>Name:</b> <?= $row['user_name'] ?></p>
    <p><b>Email:</b> <?= $row['email'] ?></p>
    <p><b>Date:</b> <?= $row['date'] ?></p>
    <p><b>Time:</b> <?= $row['time'] ?></p>

    <a href="expert_details.php?expert=<?= urlencode($row['expert_name']) ?>">
        View Expert Details
    </a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>
