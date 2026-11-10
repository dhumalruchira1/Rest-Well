<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

$query = $conn->prepare("SELECT created_at, sleep_hours, ai_score FROM sleepdata WHERE user_email=? ORDER BY created_at ASC");
$query->bind_param("s", $email);
$query->execute();
$res = $query->get_result();

$dates = [];
$sleep_hours = [];
$ai_scores = [];
$scatter_sleep = [];
$scatter_ai = [];

while($row = $res->fetch_assoc()){
    $dates[] = $row['created_at'];
    $sleep_hours[] = $row['sleep_hours'];
    $ai_scores[] = $row['ai_score'];

    // Scatter format x,y
    $scatter_sleep[] = "{x:'".$row['created_at']."', y:".$row['sleep_hours']."}";
    $scatter_ai[] = "{x:'".$row['created_at']."', y:".$row['ai_score']."}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sleep History Graph</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { background:#0b1726; color:#fff; font-family: Arial; text-align:center; padding:20px; }
h2 { color:#7dd3fc; margin-bottom:20px; }

canvas {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    margin: 0 auto;
    display:block;
}

select, button, a.btn {
    padding:8px 12px;
    border-radius:6px;
    border:none;
    font-weight:bold;
    margin:10px;
    cursor:pointer;
}

button { background:#7dd3fc; color:#052936; }
a.btn {
    background:#7dd3fc;
    color:#052936;
    text-decoration:none;
    display:inline-block;
}

.action-box {
    margin-top: 20px;
}
</style>
</head>
<body>

<h2>🛌 Your Sleep History</h2>

<label>Select Graph Type: </label>
<select id="chartType">
    <option value="line">Line Graph</option>
    <option value="bar">Bar Graph</option>
</select>

<canvas id="sleepChart" width="800" height="400"></canvas>

<!-- ACTION BUTTONS -->
<div class="action-box">
    <a href="sleepdata.php" class="btn">⬅ Back</a>

    <form method="POST" action="export_csv.php" style="display:inline-block;">
        <button type="submit">Download CSV Report</button>
    </form>

    <form method="POST" action="export_pdf.php" style="display:inline-block;">
        <button type="submit">Download PDF Report</button>
    </form>
</div>

<script>
const ctx = document.getElementById('sleepChart').getContext('2d');

// Base data (line & bar)
const baseData = {
    labels: [<?php echo '"' . implode('","', $dates) . '"'; ?>],
    datasets: [
        {
            label: 'Sleep Hours',
            data: [<?php echo implode(',', $sleep_hours); ?>],
            borderColor: 'cyan',
            backgroundColor: 'rgba(0,255,255,0.3)',
            fill: true,
            tension: 0.3,
            pointRadius: 5
        },
        {
            label: 'AI Score',
            data: [<?php echo implode(',', $ai_scores); ?>],
            borderColor: 'pink',
            backgroundColor: 'rgba(255,182,193,0.3)',
            fill: true,
            tension: 0.3,
            pointRadius: 5
        }
    ]
};

// Scatter data
const scatterData = {
    datasets: [
        {
            label: 'Sleep Hours',
            data: [<?php echo implode(',', $scatter_sleep); ?>],
            borderColor: 'cyan',
            backgroundColor: 'cyan',
            pointRadius: 6,
            showLine: false
        },
        {
            label: 'AI Score',
            data: [<?php echo implode(',', $scatter_ai); ?>],
            borderColor: 'pink',
            backgroundColor: 'pink',
            pointRadius: 6,
            showLine: false
        }
    ]
};

let chartType = document.getElementById('chartType').value;

let sleepChart = new Chart(ctx, {
    type: chartType,
    data: baseData,
    options: { responsive:true }
});

document.getElementById('chartType').addEventListener('change', function() {
    sleepChart.destroy();

    if (this.value === "scatter") {
        sleepChart = new Chart(ctx, {
            type: "scatter",
            data: scatterData,
            options: { responsive:true }
        });
    } else {
        sleepChart = new Chart(ctx, {
            type: this.value,
            data: baseData,
            options: { responsive:true }
        });
    }
});
</script>

</body>
</html>
