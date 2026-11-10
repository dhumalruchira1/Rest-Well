<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}
$email = $_SESSION['email'];

// Experts array
$experts = [
    ['name'=>'Dr. Maya Deshmukh','speciality'=>'Nutrition & Dietitian','experience'=>'10 years','description'=>'Expert in healthy diet planning and wellness programs.'],
    ['name'=>'Dr. Rajiv Kumar','speciality'=>'Physiotherapy','experience'=>'12 years','description'=>'Specialist in physical therapy, rehabilitation and sports injuries.'],
    ['name'=>'Sumit Patil','speciality'=>'Mental Health Coach','experience'=>'8 years','description'=>'Focuses on stress management and mental wellness techniques.'],
    ['name'=>'Dr. Pooja Kulkarni','speciality'=>'Cardiologist','experience'=>'15 years','description'=>'Expert in heart health, lifestyle diseases and preventive cardiology.'],
    ['name'=>'Suhas Joshi','speciality'=>'Fitness Trainer','experience'=>'7 years','description'=>'Personalized fitness programs and strength training specialist.'],
    ['name'=>'Anjali Sharma','speciality'=>'Yoga & Mindfulness','experience'=>'9 years','description'=>'Yoga instructor with mindfulness and meditation coaching.'],
];

if($_SERVER['REQUEST_METHOD']=='POST'){

    // Save user + appointment info
    $_SESSION['consult_data'] = [
        'user_name'   => $_POST['user_name'],
        'email'       => $_POST['email'],
        'expert_name' => $_POST['expert_name'],
        'date'        => $_POST['date'],
        'time'        => $_POST['time']
    ];

    // Redirect with selected expert name
    $expertName = urlencode($_POST['expert_name']);
    header("Location: expert_meet.php?expert=$expertName");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Consult a Specialist - Restwell</title>
<style>
/* Same styling as before */
body { margin:0; font-family:Arial,sans-serif; background:#0b1726; color:#fff; }
header { padding:20px; display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.4); position:sticky; top:0; z-index:10; }
header h2 { margin:0; color:#7dd3fc; }
nav a { margin:0 12px; text-decoration:none; color:#9fd8ff; font-weight:bold; }
nav a:hover {color:#fff;}
.section-title { margin-top:40px; font-size:28px; text-align:center; font-weight:bold; }
.section-sub { text-align:center; color:#9fd8ff; margin-bottom:30px; font-size:16px; }
.expert-container { display:flex; flex-wrap:wrap; justify-content:center; gap:20px; margin-bottom:50px; }
.expert-card { background: rgba(255,255,255,0.06); padding:20px; border-radius:12px; width:260px; box-shadow:0 8px 24px rgba(0,0,0,0.35); transition:0.3s; cursor:pointer; }
.expert-card:hover { box-shadow:0 12px 28px rgba(0,0,0,0.5); }
.expert-card h3 { margin:0 0 5px 0; color:#7dd3fc; }
.expert-card p { margin:5px 0; font-size:14px; color:#cce3ff; }
.expert-details { display:none; margin-top:10px; font-size:13px; color:#9fd8ff; }
.form-wrapper { display:flex; justify-content:center; margin-bottom:60px; }
.form-card { background: rgba(255,255,255,0.06); padding:30px; border-radius:12px; width:400px; box-shadow:0 8px 24px rgba(0,0,0,0.35); transition:0.3s; }
.form-card:hover { box-shadow:0 12px 28px rgba(0,0,0,0.5); }
.form-card h2 { text-align:center; margin-bottom:25px; color:#7dd3fc; }
.form-row { margin-bottom:15px; display:flex; flex-direction:column; }
input, select { padding:10px; border-radius:8px; background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); color:#fff; font-size:14px; }
input:focus, select:focus { outline:none; border-color:#7dd3fc; }
.btn-submit { background:linear-gradient(90deg,#73b3ff,#7dd3fc); color:#052936; padding:12px; width:100%; border:none; border-radius:8px; margin-top:20px; font-weight:bold; cursor:pointer; transition:0.3s; }
.btn-submit:hover { transform:scale(1.03); }
footer { text-align:center; margin-top:40px; margin-bottom:20px; color:#9fd8ff; font-size:14px; }
@media(max-width:768px){ .form-card{ width:90%; } .expert-card{ width:90%; } }
</style>
<script>
function toggleDetails(index){
    var details = document.getElementById('details-'+index);
    details.style.display = details.style.display === 'block' ? 'none' : 'block';
}
</script>
</head>
<body>

<header>
  <h2>🌙 Restwell</h2>
  <nav>
    <a href="index.php">Home</a>
    <a href="exercise.php">Exercises</a>
    <a href="health book.php">Health Books</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php" style="color:#ff9f9f;">Logout</a>
  </nav>
</header>

<div class="section-title">Meet Our Experts</div>
<div class="section-sub">Click on any expert to see their full information</div>

<div class="expert-container">
<?php foreach($experts as $i => $expert): ?>
    <div class="expert-card" onclick="toggleDetails(<?php echo $i; ?>)">
        <h3><?php echo $expert['name']; ?></h3>
        <p><?php echo $expert['speciality']; ?></p>
        <p>Experience: <?php echo $expert['experience']; ?></p>
        <div class="expert-details" id="details-<?php echo $i; ?>">
            <p><?php echo $expert['description']; ?></p>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="section-title">Book Your Appointment</div>
<div class="section-sub">Select an expert and convenient time</div>

<div class="form-wrapper">
<form action="" method="POST" class="form-card">
    <h2>Book Appointment</h2>

    <div class="form-row">
        <label>Your Full Name</label>
        <input type="text" name="user_name" required>
    </div>

    <div class="form-row">
        <label>Your Email</label>
        <input type="email" name="email" value="<?php echo $email; ?>" readonly>
    </div>

    <div class="form-row">
        <label>Select Expert</label>
        <select name="expert_name" required>
            <option value="">Choose Expert</option>
            <?php foreach($experts as $expert): ?>
                <option value="<?php echo $expert['name']; ?>"><?php echo $expert['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-row">
        <label>Select Date</label>
        <input type="date" name="date" required>
    </div>

    <div class="form-row">
        <label>Select Time</label>
        <input type="time" name="time" required>
    </div>

    <button type="submit" class="btn-submit">Next</button>
</form>
</div>

<footer>© 2025 Restwell. All rights reserved.</footer>
</body>
</html>
