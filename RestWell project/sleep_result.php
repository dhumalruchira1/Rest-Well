<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

// get id from query param
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<script>alert('Invalid result id'); window.location.href='dashboard.php';</script>";
    exit;
}

// fetch the saved record
$stmt = $conn->prepare("SELECT * FROM sleepdata WHERE id = ? AND user_email = ? LIMIT 1");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$res = $stmt->get_result();
$rec = $res->fetch_assoc();

if (!$rec) {
    echo "<script>alert('Record not found'); window.location.href='dashboard.php';</script>";
    exit;
}

$ai_score = intval($rec['ai_score']);

// determine condition & suggestion
if ($ai_score >= 75) {
    $condition = "Excellent 🌟";
    $suggestion = "Your sleep is healthy — keep your routine.";
    $consult = "No consultant needed.";
} elseif ($ai_score >= 50) {
    $condition = "Average 🙂";
    $suggestion = "Improve consistency & reduce screens before bed.";
    $consult = "Optional consultation if issues persist.";
} else {
    $condition = "Poor 😟";
    $suggestion = "Follow sleep hygiene, consider consulting a specialist.";
    $consult = "Consultation recommended.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sleep Result — RestWell</title>
    <style>
        body { 
            background:#0b1726; 
            color:#fff; 
            font-family:Arial, sans-serif; 
            padding:30px; 
        }
        .box { 
            max-width:600px; 
            margin:auto; 
            background:rgba(255,255,255,0.05); 
            padding:25px; 
            border-radius:12px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        .score { font-size:48px; color:#4ade80; font-weight:bold; }
        .small { color:#cfeff7; opacity:0.8; }
        .btn { 
            display:inline-block; 
            padding:10px 14px; 
            background:linear-gradient(90deg,#73b3ff,#4ade80); 
            color:#052936; 
            border-radius:8px; 
            text-decoration:none; 
            font-weight:bold; 
            margin-top:12px;
        }
        .chart-container {
            width: 250px;
            height: 250px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>RestWell — Sleep Analysis Result</h2>
    <p class="small">Saved at: <?php echo htmlspecialchars($rec['created_at'] ?? ''); ?></p>

    <div style="display:flex; gap:20px; align-items:center; flex-wrap:wrap; margin-top:15px;">
        <div style="flex:1; min-width:150px;">
            <div class="score"><?php echo $ai_score; ?></div>
            <div style="margin-top:8px;"><strong>Condition:</strong> <?php echo $condition; ?></div>
        </div>

        <div style="flex:2; min-width:200px;">
            <p><strong>Hours:</strong> <?php echo htmlspecialchars($rec['sleep_hours']); ?> hrs</p>
            <p><strong>Quality:</strong> <?php echo htmlspecialchars($rec['sleep_quality']); ?></p>
            <p><strong>Stress:</strong> <?php echo htmlspecialchars($rec['stress_level']); ?></p>
            <p><strong>Bedtime:</strong> <?php echo htmlspecialchars($rec['bedtime']); ?> &nbsp; <strong>Wake:</strong> <?php echo htmlspecialchars($rec['wakeup']); ?></p>
        </div>
    </div>

    <h3>Your Sleep Score</h3>
    <div class="chart-container">
        <canvas id="sleepChart"></canvas>
    </div>

    <hr style="border:0; border-top:1px solid rgba(255,255,255,0.06); margin:18px 0;">

    <h3>Suggestion</h3>
    <p><?php echo $suggestion; ?></p>

    <h3>Consultation</h3>
    <p><?php echo $consult; ?></p>

    <a class="btn" href="dashboard.php">Back to Dashboard</a>
    &nbsp;
    <a class="btn" href="consult.php?ref=auto&id=<?php echo $id; ?>">Book Consultant</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('sleepChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Score', 'Remaining'],
        datasets: [{
            data: [<?php echo $ai_score; ?>, <?php echo 100 - $ai_score; ?>],
            backgroundColor: ['#4ade80', '#1e293b'],
            borderWidth: 2,
            borderColor: '#0b1726'
        }]
    },
    options: {
        cutout: '70%',
        plugins: {
            legend: { display: false }
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>
