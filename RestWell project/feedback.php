<?php
// feedback.php
// Single-file feedback UI + AJAX endpoints (submit + fetch)

// Adjust this require path if your db.php is elsewhere
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php'; // this file must set up $conn = new mysqli(...);

// Set timezone for created_at consistency
date_default_timezone_set('Asia/Kolkata');

// ======= AJAX: Submit feedback =======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
    header('Content-Type: application/json; charset=utf-8');

    // Basic server-side validation & sanitize
    $userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $category = isset($_POST['category']) ? trim($_POST['category']) : 'other';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $contactAllowed = isset($_POST['contact_allowed']) && ($_POST['contact_allowed'] === '1' || $_POST['contact_allowed'] === 'on') ? 1 : 0;

    // Validate
    $errors = [];
    if ($userId === '') $errors[] = "User ID is required.";
    if ($rating < 1 || $rating > 5) $errors[] = "Rating must be between 1 and 5.";
    if ($message === '') $errors[] = "Feedback message is required.";
    if ($date === '') $errors[] = "Date is required.";

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    }

    // Prepare insert
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, rating, category, message, date, contact_allowed) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status'=>'error','errors'=>['Prepare failed: ' . $conn->error]]);
        exit;
    }
    $stmt->bind_param("sisssi", $userId, $rating, $category, $message, $date, $contactAllowed);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'errors' => ['Execute failed: ' . $stmt->error]]);
    }
    $stmt->close();
    exit;
}

// ======= AJAX: Fetch feedback =======
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch') {
    header('Content-Type: application/json; charset=utf-8');

    $result = $conn->query("SELECT id, user_id, rating, category, message, contact_allowed, date, created_at FROM feedback ORDER BY id DESC LIMIT 200");
    $rows = [];
    if ($result) {
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        $result->free();
    }
    echo json_encode($rows);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RestWell - Feedback</title>

    <!-- Bootstrap (kept for layout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- ===== Custom Dark Theme CSS ===== -->
    <style>
    :root{
        --bg: #071520;
        --panel: rgba(16,36,56,0.55);
        --panel-2: rgba(20,40,68,0.45);
        --glass-border: rgba(255,255,255,0.04);
        --muted: #98a6b8;
        --accent: #1a73e8;
        --accent-2: #4aa3ff;
        --success: #2ecc71;
    }

    html,body{
        height:100%;
        background: linear-gradient(180deg, #041221 0%, #071520 50%, #0b2a40 100%);
        color: #e6f1fb;
        font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        -webkit-font-smoothing:antialiased;
    }

    .navbar{
        background: linear-gradient(90deg, rgba(10,22,34,0.8), rgba(12,24,44,0.85));
        border-bottom: 1px solid rgba(255,255,255,0.03);
        box-shadow: 0 6px 18px rgba(2,6,23,0.6);
    }
    .navbar-brand { color:#eaf6ff !important; font-weight:700; letter-spacing:0.2px; }

    .feedback-container {
        max-width: 1000px;
        margin: 18px auto;
        padding: 18px;
    }

    .feedback-card {
        background: var(--panel);
        border-radius: 14px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 30px rgba(2,8,20,0.6);
        overflow: hidden;
    }

    .feedback-header{
        padding: 22px;
        background: linear-gradient(135deg, rgba(12,30,50,0.95), rgba(13,31,52,0.85));
        color: #eaf6ff;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .feedback-header p { color: var(--muted); margin-bottom:0; }

    .card-body {
        padding: 20px;
    }

    label { color: #d7e9fb; font-weight:600; }
    .form-text { color: var(--muted); }

    .form-control, .form-select, textarea {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        color: #eaf6ff;
        border-radius:10px;
        box-shadow: none;
    }
    .form-control::placeholder { color: rgba(230,241,251,0.45); }

    .form-control:focus, .form-select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 6px 18px rgba(177, 185, 193, 0.12);
        border-color: var(--accent-2);
        background: rgba(255,255,255,0.04);
        color: #b9b0b0ff;
    }

    .btn-primary {
        background: linear-gradient(180deg, var(--accent), var(--accent-2));
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        box-shadow: 0 8px 24px rgba(26,115,232,0.18);
    }
    .btn-outline-secondary {
        border-radius: 10px;
        color: #cfe9ff;
        border-color: rgba(255,255,255,0.06);
        background: transparent;
    }

    /* Stars */
    .star-rating { font-size: 24px; color: rgba(255,255,255,0.2); cursor:pointer; }
    .star-rating .star { padding: 0 6px; transition: transform .12s ease, color .12s ease; }
    .star-rating .star:hover { transform: translateY(-3px); }
    .star.active i, .star:hover i { color: #f1c40f !important; }

    /* Feedback list */
    .feedback-list {
        max-height: 420px;
        overflow-y: auto;
        padding-right: 8px;
    }
    .feedback-item {
        border-left: 4px solid var(--accent);
        background: rgba(233, 236, 237, 0.02);
        padding: 14px;
        margin-bottom: 14px;
        border-radius: 8px;
    }
    .feedback-item h5 { margin-bottom:6px; color:#eaf6ff; }
    .feedback-meta { color: var(--muted); font-size: 0.88rem; }

    /* Badge for category */
    .badge-category { background: rgba(234, 239, 241, 0.29); color: #cfe9ff; border-radius: 8px; padding:6px 10px; font-weight:600; }

    /* Notification toast */
    .notification {
        position: fixed;
        top: 22px;
        right: 22px;
        z-index: 9999;
        display: none;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0 12px 40px rgba(2,8,20,0.6);
        color: #eaf6ff;
    }
    .notification.success { background: linear-gradient(90deg,#1abc9c,#16a085); }
    .notification.error { background: linear-gradient(90deg,#e74c3c,#c0392b); }

    /* Responsive tweaks */
    @media (max-width: 768px){
        .feedback-container { padding: 12px; }
        .star-rating { font-size: 20px; }
    }
    /* ===== FIX: Recent Feedback Section Too Dark & Text Invisible ===== */

/* Lighter glass background for feedback items */
.feedback-item {
    background: rgba(103, 80, 80, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-left: 4px solid var(--accent) !important;
}

/* User name text brighter */
.feedback-item h5 {
    color: #f2f7ff !important;
    font-weight: 600;
}

/* Feedback message text brighter */
.feedback-item p {
    color: rgba(255, 255, 255, 0.88) !important;
    font-size: 0.95rem;
}

/* Date + small meta text improvement */
.feedback-meta {
    color: rgba(255, 255, 255, 0.70) !important;
    font-size: 0.86rem;
}

/* Category badge fix: more visible */
.badge-category {
    background: rgba(185, 194, 201, 0.76) !important;
    border: 1px solid rgba(0, 140, 255, 0.55) !important;
    color: #eaf6ff !important;
}

/* Feedback list scroll container (remove dark bg) */
.feedback-list {
    background: transparent !important;
}
.feedback-card .card-header h4 {
    color: #eaf2ff !important;
    opacity: 1 !important;
    filter: brightness(1.7) !important;
    position: relative;
    z-index: 10 !important;
}

.feedback-card .card-header {
    background: rgba(0,0,0,0.1) !important;
    backdrop-filter: none !important;
}

.feedback-card .card-header * {
    mix-blend-mode: normal !important;
}


    </style>
</head>
<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="fas fa-moon"></i> RestWell — Feedback</a>
  </div>
</nav>

<!-- Notification -->
<div id="app-notify" class="notification success" role="alert">
    <span id="notify-text">Saved</span>
</div>

<div class="feedback-container">

    <!-- FORM CARD -->
    <div class="card feedback-card mb-4">
        <div class="feedback-header">
            <h2 class="mb-1">Share Your Feedback</h2>
            <p class="mb-0">Help us improve RestWell — your input matters.</p>
        </div>
        <div class="card-body">
            <form id="feedback-form" autocomplete="off">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="user-id" class="form-label">User ID</label>
                        <input type="text" id="user-id" name="user_id" class="form-control" placeholder="e.g. RW2023001" required>
                        <div class="form-text">Enter your RestWell user identifier</div>
                    </div>

                    <div class="col-md-6">
                        <label for="feedback-date" class="form-label">Date</label>
                        <input type="date" id="feedback-date" name="date" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Rating</label>
                        <div class="star-rating mb-2" id="star-rating">
                            <span class="star" data-rating="1"><i class="far fa-star"></i></span>
                            <span class="star" data-rating="2"><i class="far fa-star"></i></span>
                            <span class="star" data-rating="3"><i class="far fa-star"></i></span>
                            <span class="star" data-rating="4"><i class="far fa-star"></i></span>
                            <span class="star" data-rating="5"><i class="far fa-star"></i></span>
                        </div>
                        <input type="hidden" id="rating-value" name="rating" required>
                        <div id="rating-text" class="form-text">Click on stars to rate your experience</div>
                    </div>

                    <div class="col-md-6">
                        <label for="feedback-category" class="form-label">Feedback Category</label>
                        <select id="feedback-category" name="category" class="form-select">
                            <option value="general">General Feedback</option>
                            <option value="sleep-tracking">Sleep Tracking</option>
                            <option value="consultant">Consultant Service</option>
                            <option value="exercise">Exercise Recommendations</option>
                            <option value="website">Website Experience</option>
                            <option value="payment">Payment Process</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="feedback-message" class="form-label">Your Feedback</label>
                        <textarea id="feedback-message" name="message" class="form-control" rows="5" placeholder="Please share your experience, suggestions, or issues..." required></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="contact-permission" name="contact_allowed">
                            <label class="form-check-label" for="contact-permission">
                                I give permission to contact me regarding this feedback
                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    <!-- FEEDBACK LIST CARD -->
    <div class="card feedback-card">
        <div class="card-header bg-transparent border-0">
            <h4 class="mb-0">Recent Feedback</h4>
        </div>
        <div class="card-body">
            <div id="feedback-list" class="feedback-list">
                <!-- dynamic -->
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===== JavaScript: star rating, submit, fetch & UI ===== -->
<script>
(function(){

    // Utils: show & hide notify
    function showNotify(msg, type='success') {
        const notify = document.getElementById('app-notify');
        const txt = document.getElementById('notify-text');
        txt.textContent = msg;
        notify.className = 'notification ' + (type === 'success' ? 'success' : 'error');
        notify.style.display = 'block';
        clearTimeout(window._notifyTimer);
        window._notifyTimer = setTimeout(()=>{ notify.style.display = 'none'; }, 4500);
    }

    // Set default date = today
    document.getElementById('feedback-date').valueAsDate = new Date();

    // Star rating logic
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingValue = document.getElementById('rating-value');
    const ratingText = document.getElementById('rating-text');

    const ratingTexts = {
        1: "Poor - Very dissatisfied",
        2: "Fair - Some issues",
        3: "Good - Met expectations",
        4: "Very Good - Above expectations",
        5: "Excellent - Outstanding experience"
    };

    stars.forEach((star, idx) => {
        star.addEventListener('click', function() {
            const r = parseInt(this.getAttribute('data-rating'));
            ratingValue.value = r;
            updateStars(r);
            ratingText.textContent = ratingTexts[r];
        });
        star.addEventListener('mouseover', function(){
            const r = parseInt(this.getAttribute('data-rating'));
            highlightStars(r);
        });
        star.addEventListener('mouseout', function(){
            const current = parseInt(ratingValue.value) || 0;
            highlightStars(current);
        });
    });

    function updateStars(r) {
        stars.forEach(s => {
            const val = parseInt(s.getAttribute('data-rating'));
            if (val <= r) {
                s.classList.add('active');
                s.innerHTML = '<i class="fas fa-star"></i>';
            } else {
                s.classList.remove('active');
                s.innerHTML = '<i class="far fa-star"></i>';
            }
        });
    }
    function highlightStars(r) {
        stars.forEach(s => {
            const val = parseInt(s.getAttribute('data-rating'));
            if (val <= r) {
                s.innerHTML = '<i class="fas fa-star"></i>';
            } else {
                s.innerHTML = '<i class="far fa-star"></i>';
            }
        });
    }

    // Fetch feedback from server
    async function loadFeedback(){
        try {
            const res = await fetch(window.location.pathname + '?action=fetch', { cache: 'no-store' });
            if (!res.ok) throw new Error('Failed to load');
            const data = await res.json();
            displayFeedback(data);
        } catch (err) {
            console.error(err);
            // showNotify('Unable to load feedback', 'error');
        }
    }

    
    // Display feedback array
function displayFeedback(arr){
    const list = document.getElementById('feedback-list');
    list.innerHTML = '';

    // ---------- ADD STATIC PRE-LOADED FEEDBACK ----------
    const staticFeedback = [
        {
            user_id: "RW99881",
            rating: 5,
            category: "Website Experience",
            message: "The new dark theme looks super premium! Loved the animations and smooth UI. The feedback form is also very easy to use.",
            date: "2025-02-01",
            contact_allowed: 0
        },
        {
            user_id: "RW88221",
            rating: 4,
            category: "Consultant",
            message: "Consultant session was very helpful. Got clear guidance about sleep schedule. Thank you RestWell!",
            date: "2025-01-28",
            contact_allowed: 1
        },
        {
            user_id: "RW55321",
            rating: 5,
            category: "Sleep Tracking",
            message: "Great platform! The sleep tracking features are improving day by day. Notifications are accurate.",
            date: "2025-01-20",
            contact_allowed: 0
        },
        {
            user_id: "RW44009",
            rating: 4,
            category: "Exercise",
            message: "Exercise suggestions are nice. I like the simple breathing exercises and stretches.",
            date: "2025-01-15",
            contact_allowed: 0
        },
        {
            user_id: "RW77113",
            rating: 5,
            category: "General Feedback",
            message: "Overall great experience. The UI is clean and minimalist. Looking forward to more updates!",
            date: "2025-01-05",
            contact_allowed: 1
        }
    ];

    // First show static feedback
    renderFeedbackItems(staticFeedback, list);

    // Now show database feedback (if any)
    if (arr && arr.length > 0) {
        renderFeedbackItems(arr, list);
    }
}

// Helper: Render feedback items to list
function renderFeedbackItems(arr, list){
    arr.forEach(item => {
        const dateObj = new Date(item.date + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('en-IN', { year:'numeric', month:'short', day:'numeric' });

        let starsHtml = '';
        const rating = parseInt(item.rating) || 0;
        for (let i=1;i<=5;i++){
            if (i <= rating) starsHtml += '<i class="fas fa-star" style="color:#f1c40f;margin-right:4px;"></i>';
            else starsHtml += '<i class="far fa-star" style="color:rgba(226, 232, 237, 0.12);margin-right:4px;"></i>';
        }

        const allowed = item.contact_allowed == 1
            ? '<span class="ms-2 text-success"><i class="fas fa-check-circle"></i> Contact allowed</span>'
            : '';

        const el = document.createElement('div');
        el.className = 'feedback-item';
        el.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0">User: ${escapeHtml(item.user_id)}</h5>
                <span class="badge badge-category">${escapeHtml(item.category)}</span>
            </div>
            <div class="mb-2">${starsHtml}</div>
            <p class="mb-2" style="white-space:pre-wrap;">${escapeHtml(item.message)}</p>
            <div class="feedback-meta">
                <span>Submitted on ${formattedDate}</span>
                ${allowed}
            </div>
        `;
        list.appendChild(el);
    });
}


    // Escape HTML to avoid XSS in display
    function escapeHtml(str){
        if (!str) return '';
        return String(str)
            .replaceAll('&','&amp;')
            .replaceAll('<','&lt;')
            .replaceAll('>','&gt;')
            .replaceAll('"','&quot;')
            .replaceAll("'",'&#39;');
    }

    // Submit form via AJAX
    const form = document.getElementById('feedback-form');
    form.addEventListener('submit', async function(e){
        e.preventDefault();

        // Validate rating
        if (!document.getElementById('rating-value').value) {
            showNotify('Please give a rating by clicking stars', 'error');
            return;
        }

        const fd = new FormData(form);
        fd.append('action', 'submit');

        try {
            const res = await fetch(window.location.pathname, {
                method: 'POST',
                body: fd
            });
            const data = await res.json();
            if (data.status === 'success') {
                showNotify('Thank you! Your feedback has been submitted.', 'success');
                form.reset();
                document.getElementById('feedback-date').valueAsDate = new Date();
                updateStars(0);
                document.getElementById('rating-text').textContent = 'Click on stars to rate your experience';
                // reload feedback list
                await loadFeedback();
            } else {
                // show server errors
                const errMsg = data.errors ? data.errors.join(' | ') : 'Submission failed';
                showNotify(errMsg, 'error');
            }
        } catch (err) {
            console.error(err);
            showNotify('An error occurred while submitting', 'error');
        }
    });

    // Initialize
    loadFeedback();

})();
</script>
</body>
</html>
