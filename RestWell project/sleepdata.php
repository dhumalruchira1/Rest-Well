<?php 
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php'; 

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];
$show_error = "";

// ---- PROCESS FORM ----
if (isset($_POST['submit'])) {

    $hours = isset($_POST['hours']) ? floatval($_POST['hours']) : 0;
    $quality = $_POST['quality'] ?? "";
    $mood = $_POST['mood'] ?? "";
    $consistency = $_POST['consistency'] ?? "";
    $bedtime = $_POST['bedtime'] ?? "";
    $wake = $_POST['wake'] ?? "";
    $screen = $_POST['screen'] ?? "";
    $caffeine = $_POST['caffeine'] ?? "";
    $snore = $_POST['snore'] ?? "";
    $stress = $_POST['stress'] ?? "";
    $exercise = $_POST['exercise'] ?? "";
    $dream = $_POST['dream'] ?? "";
    $wakefreq = $_POST['wakefreq'] ?? "";
    $noise = $_POST['noise'] ?? "";

    $score = 0;

    if ($hours >= 7 && $hours <= 9) $score += 30;
    else if ($hours >= 5 && $hours < 7) $score += 20;
    else $score += 10;

    $quality_score = ["Excellent 😴✨" => 25, "Good 🙂" => 20, "Average 😐" => 10, "Poor 😫" => 5];
    $score += $quality_score[$quality] ?? 0;

    $mood_score = ["Happy 😊" => 10, "Fresh 🌼" => 8, "Normal 🙂" => 6, "Tired 😩" => 3, "Low Energy 😒" => 2];
    $score += $mood_score[$mood] ?? 0;

    $stress_score = ["Low" => 15, "Moderate" => 10, "High" => 5];
    $score += $stress_score[$stress] ?? 0;

    $score += ($screen == "No") ? 10 : 5;
    $score += ($caffeine == "No") ? 10 : 5;

    $wake_score = ["0 Times" => 10, "1–2 Times" => 6, "3+ Times" => 3];
    $score += $wake_score[$wakefreq] ?? 0;

    $noise_score = ["Quiet" => 10, "Moderate Noise" => 5, "Loud Noise" => 2];
    $score += $noise_score[$noise] ?? 0;

    $ai_score = intval($score);

    $query = $conn->prepare("INSERT INTO sleepdata 
        (user_email, sleep_hours, sleep_quality, mood, consistency, bedtime, wakeup, screen_time, caffeine,
         snore, stress_level, exercise, dreams, wake_frequency, noise_level, ai_score, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
    );

    if ($query === false) {
        $show_error = "Database prepare failed: " . htmlspecialchars($conn->error);
    } else {
        $bind = $query->bind_param(
            "sisssssssssssssi",
            $email, $hours, $quality, $mood, $consistency, $bedtime, $wake, $screen, $caffeine,
            $snore, $stress, $exercise, $dream, $wakefreq, $noise, $ai_score
        );

        if ($bind === false) {
            $show_error = "Bind failed: " . htmlspecialchars($query->error);
        } else {
            if ($query->execute()) {
                $last_id = $query->insert_id;
                header("Location: sleep_result.php?id=" . $last_id);
                exit;
            } else {
                $show_error = "Execute failed: " . htmlspecialchars($query->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sleep Data — RestWell</title>
<style>
    /* ===== Buttons Smooth Click Effect ===== */
button, .btn {
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.3s ease;
    outline: none;
}

/* Normal Button Style */
button {
    background-color: #1e88e5;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Hover Effect */
button:hover {
    background-color: #42a5f5;
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

/* Click / Active Effect */
button:active {
    transform: translateY(1px) scale(0.97);
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

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

    /* General body & container */
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(160deg, #0b1726, #1f2a44);
        color: #fff;
    }
    .container {
        max-width: 700px;
        margin: 50px auto;
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 40px;
        backdrop-filter: blur(12px);
        box-shadow: 0 0 30px rgba(255,255,255,0.1);
    }
    h2 {
        text-align: center;
        font-size: 28px;
        margin-bottom: 5px;
        color: #a0e9ff;
        text-shadow: 0 0 12px #73b3ff;
    }
    p {
        text-align: center;
        font-size: 16px;
        color: #cdd7e0;
        margin-bottom: 30px;
    }

    /* Input styling */
    label {
        display: block;
        margin: 15px 0 6px;
        font-weight: 600;
        color: #b0dfff;
    }
    input[type="number"], input[type="time"], select {
        width: 100%;
        padding: 12px 15px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.08);
        color: #fff;
        font-size: 15px;
        transition: 0.3s;
        outline: none;
    }
    input[type="number"]:focus, input[type="time"]:focus, select:focus {
        border-color: #73b3ff;
        box-shadow: 0 0 12px #73b3ff66;
    }

    /* Button */
    button {
        background: linear-gradient(90deg,#73b3ff,#7dd3fc);
        padding: 14px;
        border: 0;
        border-radius: 12px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 25px;
        width: 100%;
        color: #052936;
        font-size: 16px;
        transition: 0.3s;
    }
    button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px #7dd3fc;
    }

    /* Error styling */
    .error {
        background: #3b0f0f;
        padding: 12px;
        border-radius: 10px;
        color: #ffd6d6;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Collapsible sections */
    .section-header {
        background: rgba(255,255,255,0.08);
        padding: 12px 15px;
        border-radius: 12px;
        cursor: pointer;
        margin-top: 20px;
        font-weight: bold;
        color: #b0e0ff;
        transition: 0.3s;
    }
    .section-header:hover {
        background: rgba(115,179,255,0.2);
    }
    .section-content {
        display: none;
        margin-top: 10px;
        animation: fadeIn 0.5s forwards;
    }
    @keyframes fadeIn {
        from {opacity:0;}
        to {opacity:1;}
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".section-header").forEach(function(header) {
            header.addEventListener("click", function() {
                const content = this.nextElementSibling;
                content.style.display = content.style.display === "block" ? "none" : "block";
            });
        });
    });
</script>
</head>
<body>
<div class="container">
    <h2>Sleep Health Assessment 🌙</h2>
    <p>Complete the form to analyze your sleep quality professionally</p>

    <?php if (!empty($show_error)): ?>
        <div class="error"><?php echo $show_error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Hours Slept</label>
        <input type="number" name="hours" step="0.1" required>

        <div class="section-header">Daily Sleep Details ⬇️</div>
        <div class="section-content">
            <label>Sleep Quality</label>
            <select name="quality" required>
                <option>Excellent 😴✨</option>
                <option>Good 🙂</option>
                <option>Average 😐</option>
                <option>Poor 😫</option>
            </select>

            <label>Mood After Waking</label>
            <select name="mood" required>
                <option>Happy 😊</option>
                <option>Fresh 🌼</option>
                <option>Normal 🙂</option>
                <option>Tired 😩</option>
                <option>Low Energy 😒</option>
            </select>

            <label>Sleep Consistency</label>
            <select name="consistency">
                <option>Regular</option>
                <option>Irregular</option>
            </select>

            <label>Bedtime</label>
            <input type="time" name="bedtime" required>

            <label>Wake-up Time</label>
            <input type="time" name="wake" required>
        </div>

        <div class="section-header">Habits & Environment ⬇️</div>
        <div class="section-content">
            <label>Screen Time Before Bed?</label>
            <select name="screen">
                <option>No</option>
                <option>Yes (Last 30 mins)</option>
                <option>Yes (1 hour before)</option>
            </select>

            <label>Caffeine Intake (Evening)</label>
            <select name="caffeine">
                <option>No</option>
                <option>Yes – Tea</option>
                <option>Yes – Coffee</option>
                <option>Yes – Energy Drink</option>
            </select>

            <label>Snoring</label>
            <select name="snore">
                <option>No</option>
                <option>Yes (Mild)</option>
                <option>Yes (Loud)</option>
            </select>

            <label>Stress Level</label>
            <select name="stress">
                <option>Low</option>
                <option>Moderate</option>
                <option>High</option>
            </select>

            <label>Exercise Before Sleep?</label>
            <select name="exercise">
                <option>No</option>
                <option>Light Yoga</option>
                <option>Walking</option>
                <option>Heavy Workout</option>
            </select>

            <label>Dream Activity</label>
            <select name="dream">
                <option>No Dreams</option>
                <option>Normal Dreams</option>
                <option>Nightmares</option>
                <option>Very Vivid Dreams</option>
            </select>

            <label>Wake Frequency</label>
            <select name="wakefreq">
                <option>0 Times</option>
                <option>1–2 Times</option>
                <option>3+ Times</option>
            </select>

            <label>Noise Level at Night</label>
            <select name="noise">
                <option>Quiet</option>
                <option>Moderate Noise</option>
                <option>Loud Noise</option>
            </select>
        </div>

        <button type="submit" name="submit">Save Sleep Data</button>
    </form>
</div>
</body>
</html>
