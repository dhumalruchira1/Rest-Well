<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sleep Tips — RestWell</title>
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

/* Body + Gradient Animation */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(270deg, #071520, #001a2e, #0b1d3f);
    background-size: 600% 600%;
    color: #E3F2FD;
    animation: gradientBG 20s ease infinite;
    transition: background 0.5s, color 0.5s;
}

@keyframes gradientBG {
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* Back Button */
.back-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background: #1e88e5;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: 0.3s;
    z-index: 100;
}
.back-btn:hover { background: #42a5f5; transform: scale(1.05); }

h1 {
    text-align: center;
    margin-top: 70px;
    font-size: 38px;
    color: #90caf9;
    text-shadow: 0 0 12px #0d47a1;
}

.container { width: 90%; margin: auto; margin-top: 20px; }

.tabs { display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
.tab {
    padding: 12px 25px;
    background: #0d2538;
    border-radius: 20px;
    cursor: pointer;
    font-size: 16px;
    border: 1px solid #144a6f;
    transition: 0.3s;
}
.tab:hover { background: #144a6f; transform: scale(1.05);}
.active-tab { background: #1e88e5 !important; border-color: #1e88e5; }

/* Glass Cards + Hover Effects */
.glass {
    background: rgba(255,255,255,0.05);
    border-radius: 18px;
    padding: 25px;
    margin-top: 20px;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 8px 18px rgba(0,0,0,0.4);
    transition: transform 0.3s, box-shadow 0.3s;
}
.glass:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.5); }

.tip-item {
    background: #0c2335;
    padding: 15px;
    border-left: 6px solid #1e88e5;
    border-radius: 12px;
    margin: 12px 0;
    font-size: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.tip-item:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(30,136,229,0.4); }

.feature-box { background: #0d2538; padding: 20px; border-radius: 15px; margin-top: 20px; border: 1px solid #154b72; }
.feature-box h3 { margin-top: 0; color: #64b5f6; }

input, select, button {
    padding: 10px; border-radius: 10px; border: none; margin: 5px 0;
}
button { background: #1e88e5; color: white; cursor: pointer; transition: 0.3s; }
button:hover { background: #42a5f5; transform: scale(1.05); }

/* Notification Popup */
#notify {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #1e88e5;
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    display: none;
    z-index: 999;
}

/* Floating Stars Animation */
.star {
    position: absolute;
    width: 3px; height: 3px;
    background: #fff;
    border-radius: 50%;
    animation: floatStars 10s linear infinite;
    opacity: 0.7;
}

@keyframes floatStars {
    0%{ transform: translateY(0) translateX(0); opacity:0.7; }
    50%{ opacity:0.3; }
    100%{ transform: translateY(-800px) translateX(200px); opacity:0; }
}
</style>
</head>

<body>

<a href="dashboard.php" class="back-btn">⬅ Back</a>
<h1>🌙 RestWell — Premium Sleep Tips</h1>

<div class="container">

<!-- Tabs -->
<div class="tabs">
    <div class="tab active-tab" onclick="openTab('bedtime')">🌙 Bedtime Tips</div>
    <div class="tab" onclick="openTab('morning')">🌅 Morning Tips</div>
    <div class="tab" onclick="openTab('habits')">📌 Lifestyle Habits</div>
    <div class="tab" onclick="openTab('environment')">🏡 Room Setup</div>
    <div class="tab" onclick="openTab('sleepcalc')">🤖 Sleep Calculator</div>
    <div class="tab" onclick="openTab('routine')">🌙 Night Routine</div>
    <div class="tab" onclick="openTab('food')">🥦 Sleep Food</div>
    <div class="tab" onclick="openTab('weekly')">📊 Weekly Report</div>
</div>

<!-- BEDTIME -->
<div id="bedtime" class="glass tips-section">
    <div class="tip-item">📵 Avoid phones/screens 30 mins before sleep.</div>
    <div class="tip-item">🛏 Go to bed & wake up at same time daily.</div>
    <div class="tip-item">🌡 Keep bedroom cool (18–22°C).</div>
    <div class="tip-item">💧 Drink warm water or milk before bed.</div>
    <div class="tip-item">🕯 Use dim lighting & calming scents.</div>
    <div class="tip-item">🎶 Listen to soft music or nature sounds.</div>
    <div class="tip-item">🧘 Meditation for 5–10 mins relaxes mind.</div>
</div>

<!-- MORNING -->
<div id="morning" class="glass tips-section" style="display:none;">
    <div class="tip-item">☀️ Sunlight within 10 mins of waking.</div>
    <div class="tip-item">🚶 Morning walk for 10–15 mins.</div>
    <div class="tip-item">🥣 Healthy breakfast boosts energy.</div>
    <div class="tip-item">💧 Hydrate well after waking.</div>
    <div class="tip-item">🧠 Light mental exercises start your day.</div>
</div>

<!-- LIFESTYLE -->
<div id="habits" class="glass tips-section" style="display:none;">
    <div class="tip-item">☕ Avoid caffeine after 4 PM.</div>
    <div class="tip-item">🚫 No heavy meals late at night.</div>
    <div class="tip-item">😴 Avoid long naps in day.</div>
    <div class="tip-item">📖 Read instead of phone before bed.</div>
    <div class="tip-item">🏃 Light exercise daily improves sleep.</div>
</div>

<!-- ROOM SETUP -->
<div id="environment" class="glass tips-section" style="display:none;">
    <div class="tip-item">🌑 Keep room dark or dim lamps.</div>
    <div class="tip-item">🕯 Use calming scents like lavender.</div>
    <div class="tip-item">🛏 Comfortable pillow & mattress.</div>
    <div class="tip-item">🔇 Low noise environment.</div>
    <div class="tip-item">🌿 Add plants for freshness.</div>
</div>

<!-- AI SLEEP CALCULATOR -->
<div id="sleepcalc" class="glass tips-section" style="display:none;">
    <h3>🤖 AI Sleep Calculator</h3>
    <label>Bedtime:</label>
    <input type="time" id="bedtimeInput"><br>
    <label>Wake-up Time:</label>
    <input type="time" id="wakeInput"><br>
    <button onclick="calculateSleep()">Calculate Sleep Hours</button>
    <p id="sleepResult"></p>
</div>

<!-- NIGHT ROUTINE GENERATOR -->
<div id="routine" class="glass tips-section" style="display:none;">
    <h3>🌙 Night Routine Generator</h3>
    <label>Select Mood:</label>
    <select id="moodSelect">
        <option value="relax">Relaxing</option>
        <option value="productive">Productive</option>
    </select><br>
    <button onclick="generateRoutine()">Generate Routine</button>
    <p id="routineResult"></p>
</div>

<!-- FOOD THAT HELPS -->
<div id="food" class="glass tips-section" style="display:none;">
    <h3>🥦 Foods That Help You Sleep</h3>
    <div class="tip-item">🥛 Warm milk</div>
    <div class="tip-item">🍌 Bananas</div>
    <div class="tip-item">🥜 Almonds</div>
    <div class="tip-item">🌿 Chamomile tea</div>
    <div class="tip-item">🍯 Honey</div>
    <div class="tip-item">🍵 Herbal teas (Valerian root, Peppermint)</div>
</div>

<!-- WEEKLY REPORT -->
<div id="weekly" class="glass tips-section" style="display:none;">
    <h3>📊 Weekly Sleep Report</h3>
    <p>Average Sleep: 7.5 hours/day</p>
    <p>Best Sleep Day: Wednesday</p>
    <p>Consistency: 85%</p>
    <p>Next Goal: Wake-up at 6:30 AM daily</p>
</div>

<!-- Notification Popup -->
<div id="notify">⏰ Time to start winding down for sleep 🌙</div>

<!-- Floating Stars -->
<div id="stars-container"></div>

</div>

<script>
// Tabs
function openTab(tabName) {
    let sections = document.getElementsByClassName("tips-section");
    let tabs = document.getElementsByClassName("tab");
    for (let item of sections) item.style.display = "none";
    for (let t of tabs) t.classList.remove("active-tab");
    document.getElementById(tabName).style.display = "block";
    event.target.classList.add("active-tab");
}

// Sleep Calculator
function calculateSleep() {
    let start = document.getElementById("bedtimeInput").value;
    let end = document.getElementById("wakeInput").value;
    if(!start || !end){ alert("Please select both times."); return; }
    let startDate = new Date("1970-01-01T" + start + "Z");
    let endDate = new Date("1970-01-01T" + end + "Z");
    let diff = (endDate - startDate)/1000/3600;
    if(diff < 0) diff += 24;
    document.getElementById("sleepResult").innerText = "🛌 Total Sleep: " + diff.toFixed(1) + " hours";
}

// Night Routine Generator
function generateRoutine() {
    let mood = document.getElementById("moodSelect").value;
    let result = "";
    if(mood === "relax"){
        result = "🌙 Relaxing Routine: Light reading, warm shower, meditate, journal thoughts.";
    } else {
        result = "🌙 Productive Routine: Plan tomorrow, light exercise, prep clothes, calm tea.";
    }
    document.getElementById("routineResult").innerText = result;
}

// Night Mode Auto Switch + Notification
function nightModeSwitch(){
    let hour = new Date().getHours();
    if(hour >= 19 || hour <= 6){
        document.body.style.background = "#001a2e";
        document.body.style.color = "#cfd8dc";
        showNotification();
    }
}

// Show user-friendly notification
function showNotification(){
    let notif = document.getElementById("notify");
    notif.style.display = "block";
    setTimeout(()=>{ notif.style.display = "none"; }, 8000);
}

// Floating Stars Effect
function createStars(){
    let container = document.getElementById("stars-container");
    for(let i=0;i<30;i++){
        let star = document.createElement("div");
        star.classList.add("star");
        star.style.left = Math.random()*window.innerWidth+"px";
        star.style.top = Math.random()*window.innerHeight+"px";
        star.style.animationDuration = (5+Math.random()*10)+"s";
        container.appendChild(star);
    }
}

window.onload = ()=>{
    nightModeSwitch();
    createStars();
}
</script>

</body>
</html>
