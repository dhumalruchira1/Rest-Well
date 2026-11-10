<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RestWell - Sleep Exercises</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
    --primary: #2c3e50;
    --secondary: #3498db;
    --accent: #9b59b6;
    --light: #ecf0f1;
    --dark: #1c1f26;
    --success: #2ecc71;
    --warning: #f39c12;
    --danger: #e74c3c;
}

body {            
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--dark);
    color: var(--light);
    padding-top: 20px;
}

.navbar { background-color: var(--primary); box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
.navbar-brand { font-weight: 700; color: white !important; }

.exercise-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.exercise-card { border-radius: 10px; border: none; background-color: #2c3e50; color: var(--light); box-shadow: 0 5px 15px rgba(0,0,0,0.2); margin-bottom: 30px; transition: transform 0.3s, box-shadow 0.3s; }
.exercise-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
.exercise-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 10px 10px 0 0; padding: 20px; }
.section-title { position: relative; margin-bottom: 30px; padding-bottom: 15px; }
.section-title:after { content: ''; position: absolute; left: 0; bottom: 0; width: 50px; height: 3px; background-color: var(--secondary); }

.btn-primary, .btn-outline-primary, .btn-outline-secondary { border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }
.btn-primary { background-color: var(--secondary); border-color: var(--secondary); color: #fff; }
.btn-primary:hover { background-color: #2980b9; border-color: #2980b9; transform: scale(1.05); }
.btn-outline-primary { background-color: transparent; border: 2px solid var(--secondary); color: var(--secondary); }
.btn-outline-primary:hover { background-color: var(--secondary); color: #fff; transform: scale(1.05); }
.btn-outline-secondary { color: var(--light); border-color: var(--light); }
.btn-outline-secondary:hover { background-color: var(--light); color: var(--dark); transform: scale(1.05); }

.card-footer .btn-outline-primary { width: 100%; font-size: 1rem; padding: 10px 0; }
.exercise-icon { font-size: 2rem; color: var(--secondary); margin-bottom: 15px; }
.timer-display { font-size: 3rem; font-weight: bold; color: var(--secondary); text-align: center; margin: 20px 0; }
.progress-container { background-color: #34495e; border-radius: 10px; height: 10px; margin: 20px 0; }
.progress-bar { background-color: var(--secondary); height: 100%; border-radius: 10px; transition: width 0.5s; }
.ai-recommendation { background-color: #22313f; border-left: 4px solid var(--secondary); padding: 15px; border-radius: 0 5px 5px 0; margin: 20px 0; color: var(--light); }
.exercise-step { display: flex; align-items: center; margin-bottom: 15px; padding: 10px; background-color: #34495e; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); color: var(--light); }
.step-number { background-color: var(--secondary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold; }
.sleep-score { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 10px; padding: 20px; text-align: center; }
.sleep-score-value { font-size: 3rem; font-weight: bold; margin: 10px 0; }
input, select, textarea { background-color: #34495e; color: #ecf0f1; border: 1px solid #555; }
input:focus, select:focus, textarea:focus { background-color: #34495e; color: #ecf0f1; border-color: var(--secondary); outline: none; box-shadow: 0 0 5px var(--secondary); }
.form-check-label { color: #ecf0f1; }
</style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="fas fa-moon"></i> RestWell - Sleep Exercises</a>
    <div class="navbar-nav ms-auto">
      <a href="dashboard.php" class="btn btn-outline-primary mb-3" style="border-radius: 10px; font-weight: 600; padding: 10px 18px; box-shadow: 0 0 10px rgba(52,152,219,0.3);">← Back to Dashboard</a>
    </div>
  </div>
</nav>

<div class="exercise-container">
  <!-- Sleep Quality Assessment -->
  <div class="card exercise-card">
    <div class="exercise-header">
      <h2 class="mb-0">Sleep Quality Assessment</h2>
      <p class="mb-0">Complete this assessment to get personalized exercise recommendations</p>
    </div>
    <div class="card-body p-4">
      <form id="sleep-assessment">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="sleep-duration" class="form-label">Average Sleep Duration (hours)</label>
              <input type="number" class="form-control" id="sleep-duration" min="0" max="12" step="0.5" value="7">
            </div>
            <div class="mb-3">
              <label for="sleep-quality" class="form-label">Sleep Quality (1-10)</label>
              <input type="range" class="form-range" id="sleep-quality" min="1" max="10" value="6">
              <div class="d-flex justify-content-between">
                <small>Poor</small>
                <small id="quality-value">6/10</small>
                <small>Excellent</small>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Common Sleep Issues</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="difficulty-falling">
                <label class="form-check-label" for="difficulty-falling">Difficulty falling asleep</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="waking-up">
                <label class="form-check-label" for="waking-up">Waking up during the night</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="waking-early">
                <label class="form-check-label" for="waking-early">Waking up too early</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="feeling-tired">
                <label class="form-check-label" for="feeling-tired">Feeling tired after waking</label>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3">
          <label for="stress-level" class="form-label">Stress Level (1-10)</label>
          <input type="range" class="form-range" id="stress-level" min="1" max="10" value="5">
          <div class="d-flex justify-content-between">
            <small>Low</small>
            <small id="stress-value">5/10</small>
            <small>High</small>
          </div>
        </div>
        <button type="button" class="btn btn-primary btn-lg w-100" onclick="generateRecommendations()">Get Personalized Exercises</button>
      </form>
    </div>
  </div>

  <!-- AI Recommendations -->
  <div class="card exercise-card">
    <div class="card-body p-4">
      <h3 class="section-title">AI-Powered Exercise Recommendations</h3>
      <div class="ai-recommendation">
        <h5><i class="fas fa-robot text-primary me-2"></i> AI Analysis</h5>
        <p id="ai-analysis">Complete the sleep assessment above to get personalized recommendations based on your sleep patterns and issues.</p>
      </div>
      <div id="exercise-recommendations"></div>
    </div>
  </div>

  <!-- Breathing Exercise -->
  <div class="card exercise-card">
    <div class="card-body p-4">
      <h3 class="section-title">Guided Breathing Exercise</h3>
      <div class="row align-items-center">
        <div class="col-md-6 text-center">
          <div class="timer-display" id="breath-timer">5:00</div>
          <div class="progress-container">
            <div class="progress-bar" id="breath-progress" style="width: 0%"></div>
          </div>
          <div class="mb-3"><span id="breath-phase">Ready to begin</span></div>
          <button class="btn btn-primary btn-lg" id="breath-start" onclick="startBreathingExercise()">Start Exercise</button>
          <button class="btn btn-outline-secondary btn-lg" id="breath-pause" onclick="pauseBreathingExercise()" style="display:none;">Pause</button>
          <button class="btn btn-outline-secondary btn-lg" id="breath-reset" onclick="resetBreathingExercise()">Reset</button>
        </div>
        <div class="col-md-6">
          <div class="exercise-steps">
            <h5>4-7-8 Breathing Technique</h5>
            <div class="exercise-step"><div class="step-number">1</div><div>Empty your lungs completely</div></div>
            <div class="exercise-step"><div class="step-number">2</div><div>Breathe in quietly through your nose for 4 seconds</div></div>
            <div class="exercise-step"><div class="step-number">3</div><div>Hold your breath for 7 seconds</div></div>
            <div class="exercise-step"><div class="step-number">4</div><div>Exhale completely through your mouth for 8 seconds</div></div>
            <div class="exercise-step"><div class="step-number">5</div><div>Repeat this cycle 4 times</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Progressive Muscle Relaxation -->
  <div class="card exercise-card">
    <div class="card-body p-4">
      <h3 class="section-title">Progressive Muscle Relaxation</h3>
      <div class="row">
        <div class="col-md-8">
          <p>This technique involves tensing and then relaxing each muscle group in your body to promote physical relaxation.</p>
          <div id="muscle-groups"></div>
          <button class="btn btn-primary btn-lg" onclick="startMuscleRelaxation()">Start Muscle Relaxation</button>
          <button class="btn btn-outline-danger btn-lg mt-2" id="muscle-stop" onclick="stopMuscleRelaxation()">Stop Exercise</button>
        </div>
        <div class="col-md-4">
          <div class="sleep-score">
            <h5>Expected Sleep Improvement</h5>
            <div class="sleep-score-value">+22%</div>
            <p>Based on users with similar sleep patterns</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Yoga Poses -->
  <div class="card exercise-card">
    <div class="card-body p-4">
      <h3 class="section-title">Yoga Poses for Better Sleep</h3>
      <div class="row" id="yoga-poses"></div>
    </div>
  </div>
</div>

<!-- Yoga Instruction Modal -->
<div class="modal fade" id="yogaModal" tabindex="-1" aria-labelledby="yogaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="yogaModalLabel">Yoga Pose Instructions</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="yogaModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Exercise Data
const exerciseData = {
  breathing: {title:"4-7-8 Breathing", description:"This technique helps calm the nervous system.", duration:5, difficulty:"Easy"},
  muscleRelaxation: {title:"Progressive Muscle Relaxation", description:"Tense and relax muscle groups.", duration:10, difficulty:"Medium"},
  yoga: [
    {name:"Legs Up the Wall", description:"Lie on back with legs up a wall.", difficulty:"Easy", duration:"5-10 min"},
    {name:"Child's Pose", description:"Kneel and fold forward with arms extended.", difficulty:"Easy", duration:"2-5 min"},
    {name:"Reclining Bound Angle", description:"Soles together, knees out.", difficulty:"Medium", duration:"3-5 min"},
    {name:"Corpse Pose", description:"Lie flat, arms at sides, palms up.", difficulty:"Easy", duration:"5-10 min"}
  ]
};

// DOMContent Loaded
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('sleep-quality').addEventListener('input', e=>document.getElementById('quality-value').textContent=e.target.value+'/10');
  document.getElementById('stress-level').addEventListener('input', e=>document.getElementById('stress-value').textContent=e.target.value+'/10');
  generateMuscleGroups();
  generateYogaPoses();
});

// Generate Muscle Groups
function generateMuscleGroups() {
  const muscleGroups = ["Feet and toes","Calves and shins","Thighs and hips","Abdomen and lower back","Chest and upper back","Hands and fingers","Arms and shoulders","Neck and jaw","Face and forehead"];
  const container = document.getElementById('muscle-groups');
  container.innerHTML = '';
  muscleGroups.forEach((group,index)=>{
    const div=document.createElement('div');
    div.className='exercise-step';
    div.innerHTML=`<div class="step-number">${index+1}</div><div>${group} - Tense for 5 seconds, then relax for 30 seconds</div>`;
    container.appendChild(div);
  });
}

// Generate Yoga Poses
function generateYogaPoses() {
  const container=document.getElementById('yoga-poses');
  container.innerHTML='';
  exerciseData.yoga.forEach((pose,index)=>{
    const col=document.createElement('div');
    col.className='col-md-6 mb-4';
    col.innerHTML=`<div class="card h-100"><div class="card-body"><h5 class="card-title">${pose.name}</h5><p class="card-text">${pose.description}</p><div class="d-flex justify-content-between text-muted"><span><i class="fas fa-clock"></i> ${pose.duration}</span><span><i class="fas fa-signal"></i> ${pose.difficulty}</span></div></div><div class="card-footer"><button class="btn btn-outline-primary w-100" onclick="viewYogaInstructions(${index})">View Instructions</button></div></div>`;
    container.appendChild(col);
  });
}

// Yoga Instructions Modal
function viewYogaInstructions(index){
  const pose=exerciseData.yoga[index];
  let steps=[];
  switch(pose.name){
    case "Legs Up the Wall":
      steps=["Sit next to a wall with one side touching it.","Lie back and swing legs up onto wall.","Rest arms by sides, palms up.","Close eyes and breathe deeply.","Hold 5-10 min"];
      break;
    case "Child's Pose":
      steps=["Kneel with big toes touching, knees apart.","Sit back on heels, extend arms forward.","Rest forehead on floor.","Relax shoulders, breathe.","Hold 2-5 min"];
      break;
    case "Reclining Bound Angle":
      steps=["Lie on back, soles together, knees outward.","Hands on abdomen or sides.","Close eyes, relax.","Focus on breath.","Hold 3-5 min"];
      break;
    case "Corpse Pose":
      steps=["Lie flat on back, arms at sides.","Palms facing up, legs comfortably apart.","Close eyes, relax completely.","Breathe slowly and deeply.","Hold 5-10 min"];
      break;
  }
  document.getElementById('yogaModalBody').innerHTML=steps.map((s,i)=>`<div class="exercise-step"><div class="step-number">${i+1}</div><div>${s}</div></div>`).join('');
  new bootstrap.Modal(document.getElementById('yogaModal')).show();
}

// Generate Recommendations
function generateRecommendations() {
  const duration = parseFloat(document.getElementById('sleep-duration').value);
  const quality = parseInt(document.getElementById('sleep-quality').value);
  const stress = parseInt(document.getElementById('stress-level').value);
  const issues = Array.from(document.querySelectorAll('#sleep-assessment .form-check-input')).filter(c=>c.checked).map(c=>c.nextElementSibling.textContent);

  let recs=[];
  if(duration<7 || quality<6 || stress>6 || issues.length>0){
    recs.push("Start with guided breathing exercises daily.");
    recs.push("Practice progressive muscle relaxation before bed.");
    recs.push("Try recommended yoga poses for 10-15 min.");
    if(stress>7) recs.push("Consider meditation or journaling to manage stress.");
  } else {
    recs.push("Maintain current sleep routine and exercise regimen.");
  }
  document.getElementById('ai-analysis').textContent = "Based on your inputs, here are personalized recommendations:";
  const container = document.getElementById('exercise-recommendations');
  container.innerHTML = recs.map(r=>`<div class="ai-recommendation"><i class="fas fa-check-circle text-success me-2"></i>${r}</div>`).join('');
}

// Breathing Exercise Logic
let breathTime=300, breathInterval=null, breathIsRunning=false;
function startBreathingExercise(){
  if(breathIsRunning) return;
  breathIsRunning=true;
  document.getElementById('breath-start').style.display='none';
  document.getElementById('breath-pause').style.display='inline-block';
  let remaining=breathTime;
  const progressBar=document.getElementById('breath-progress');
  const display=document.getElementById('breath-timer');
  const phase=document.getElementById('breath-phase');
  const updateDisplay=()=>{let m=Math.floor(remaining/60); let s=remaining%60; display.textContent=`${m}:${s.toString().padStart(2,'0')}`; progressBar.style.width=`${((breathTime-remaining)/breathTime)*100}%`;};
  updateDisplay();
  breathInterval=setInterval(()=>{
    if(remaining<=0){clearInterval(breathInterval); breathIsRunning=false; phase.textContent="Exercise complete!"; document.getElementById('breath-start').style.display='inline-block'; document.getElementById('breath-pause').style.display='none'; return;}
    remaining--;
    if(remaining%19<4) phase.textContent="Inhale"; else if(remaining%19<11) phase.textContent="Hold"; else phase.textContent="Exhale";
    updateDisplay();
  },1000);
}
function pauseBreathingExercise(){if(breathInterval){clearInterval(breathInterval); breathIsRunning=false; document.getElementById('breath-start').style.display='inline-block'; document.getElementById('breath-pause').style.display='none';}}
function resetBreathingExercise(){pauseBreathingExercise(); breathTime=300; document.getElementById('breath-timer').textContent='5:00'; document.getElementById('breath-progress').style.width='0%'; document.getElementById('breath-phase').textContent='Ready to begin';}

// Muscle Relaxation Logic
// Progressive Muscle Relaxation - Guided Exercise
let currentMuscleIndex = 0;
let muscleTimer;

function startMuscleRelaxation() {
    const muscleGroups = [
        "Feet and toes",
        "Calves and shins",
        "Thighs and hips",
        "Abdomen and lower back",
        "Chest and upper back",
        "Hands and fingers",
        "Arms and shoulders",
        "Neck and jaw",
        "Face and forehead"
    ];

    const container = document.getElementById('muscle-groups');
    const steps = container.querySelectorAll('.exercise-step');

    // Reset previous highlights
    steps.forEach(step => step.style.backgroundColor = "#34495e");

    if (currentMuscleIndex === 0) {
        alert("Starting Progressive Muscle Relaxation exercise. We'll guide you through tensing and relaxing each muscle group.");
    }

    if (currentMuscleIndex < muscleGroups.length) {
        // Highlight current muscle group
        const currentStep = steps[currentMuscleIndex];
        currentStep.style.backgroundColor = "#9b59b6";

        // Auto-scroll to the current muscle group
        currentStep.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Initialize tense and relax times
        let tenseTime = 5;  // seconds for tensing
        let relaxTime = 30; // seconds for relaxing

        muscleTimer = setInterval(() => {
            if (tenseTime > 0) {
                currentStep.querySelector('div:last-child').textContent =
                    `${muscleGroups[currentMuscleIndex]} - Tense for ${tenseTime} seconds...`;
                tenseTime--;
            } else if (relaxTime > 0) {
                currentStep.querySelector('div:last-child').textContent =
                    `${muscleGroups[currentMuscleIndex]} - Relax for ${relaxTime} seconds...`;
                relaxTime--;
            } else {
                clearInterval(muscleTimer);
                currentMuscleIndex++;
                startMuscleRelaxation(); // Move to next muscle group
            }
        }, 1000);
    } else {
        alert("Progressive Muscle Relaxation exercise complete!");
        // Reset for next session
        currentMuscleIndex = 0;
        steps.forEach(step => {
            step.style.backgroundColor = "#34495e";
            step.querySelector('div:last-child').textContent =
                `${step.querySelector('.step-number').textContent} - Tense for 5 seconds, then relax for 30 seconds`;
        });
    }
}

// Stop PMR exercise anytime
function stopMuscleRelaxation() {
    clearInterval(muscleTimer);
    currentMuscleIndex = 0;

    const steps = document.querySelectorAll('#muscle-groups .exercise-step');
    steps.forEach(step => {
        step.style.backgroundColor = "#34495e";
        step.querySelector('div:last-child').textContent =
            `${step.querySelector('.step-number').textContent} - Tense for 5 seconds, then relax for 30 seconds`;
    });

    alert("Progressive Muscle Relaxation exercise stopped.");
}

</script>
</body>
</html>
