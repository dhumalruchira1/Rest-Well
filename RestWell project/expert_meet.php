<?php  
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['consult_data'])) {
    echo "<script>alert('Please fill the consult form first.'); window.location='consult.php';</script>";
    exit;
}

$data = $_SESSION['consult_data'];
$expert = $data['expert_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_name   = $data['user_name'];
    $email       = $data['email'];
    $expert_name = $data['expert_name'];
    $date        = $data['date'];
    $time        = $data['time'];

    $meeting_type = $_POST['meeting_type'];
    $extra_info   = $_POST['extra_info'];

    // Auto Zoom link
    $meeting_link = "";
    if ($meeting_type === "Online Meeting") {
        $meeting_link = "https://zoom.us/meet/" . rand(100000,999999);
    }

    // NEW: Selected Clinic
    $clinic_selected = "";
    if ($meeting_type === "Clinic Visit") {
        $clinic_selected = $_POST['clinic_option'];
    }

    // Insert to DB
    $sql = "INSERT INTO bookings (user_name, email, expert_name, meeting_type, meeting_link, location, date, time, additional_info, status)
            VALUES ('$user_name', '$email', '$expert_name', '$meeting_type', '$meeting_link', '$clinic_selected', '$date', '$time', '$extra_info', 'booked')";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id;
        unset($_SESSION['consult_data']);
        header("Location: success.php?id=$booking_id");
        exit;
    } 
    else {
        echo "DB Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Your Expert</title>

<style>
body {
    margin:0;
    background:#071521;
    font-family:Arial, sans-serif;
    color:white;
}
.container {
    width:90%;
    max-width:500px;
    margin:50px auto;
    padding:25px;
    background:rgba(255,255,255,0.07);
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.5);
}
h2 { text-align:center; margin-bottom:22px; color:#7dd3fc; }
label { font-size:15px; margin-bottom:6px; display:block; }
select, textarea {
    width:100%; padding:12px; border-radius:8px;
    margin-bottom:18px; background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.2); color:white;
}
.clinic-box {
    background:rgba(255,255,255,0.08);
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}
input[type=radio] { margin-right:10px; }
.btn { width:100%; padding:12px; font-size:17px; font-weight:bold;
       border-radius:8px; border:none; cursor:pointer; }
.next-btn { background:#7dd3fc; color:#052b36; }
.cancel-btn { background:#ff9f9f; color:#3a0303; margin-top:10px; display:block; text-align:center; text-decoration:none; }
</style>

<script>
function showClinics() {
    let meetingType = document.getElementById("meeting_type").value;
    document.getElementById("clinic_section").style.display = 
        (meetingType === "Clinic Visit") ? "block" : "none";
}
</script>

</head>

<body>

<div class="container">
    <h2>Consult with <?php echo $expert; ?></h2>

    <form action="" method="POST">

        <label>Select Meeting Mode</label>
        <select name="meeting_type" id="meeting_type" onchange="showClinics()" required>
            <option value="">Choose Option</option>
            <option value="Online Meeting">Online Meeting</option>
            <option value="Clinic Visit">Clinic Visit</option>
        </select>

        <!-- Clinic Choices -->
        <div id="clinic_section" style="display:none;">
            <label>Select Clinic Location</label>

            <div class="clinic-box">
                <input type="radio" name="clinic_option" value="Clinic A - MG Road, Pune" required>
                Clinic A - MG Road, Pune
            </div>

            <div class="clinic-box">
                <input type="radio" name="clinic_option" value="Clinic B - FC Road, Pune" required>
                Clinic B - FC Road, Pune
            </div>

            <div class="clinic-box">
                <input type="radio" name="clinic_option" value="Clinic C - Hinjewadi Phase 2" required>
                Clinic C - Hinjewadi Phase 2
            </div>
        </div>

        <label>Additional Information</label>
        <textarea name="extra_info" placeholder="Write any extra details..." rows="3"></textarea>

        <button type="submit" class="btn next-btn">Continue</button>
    </form>

    <a href="consult.php" class="cancel-btn">Cancel</a>
</div>

</body>
</html>
