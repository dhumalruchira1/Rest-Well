<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>RestWell — Smart Sleep & Wellness</title>
<meta name="description" content="RestWell — Sleep tracking, AI insights, guided relaxation & expert support." />
<style>
    :root{
        --bg-1:#071026;
        --bg-2:#0b1230;
        --accent-1:#7b46ff;
        --accent-2:#ff7aa2;
        --glass: rgba(255,255,255,0.06);
        --glass-strong: rgba(255,255,255,0.11);
        --text: #E9F0FF;
        --muted: #b9c4e6;
        --success: #7dd3fc;
        --card-radius: 14px;
    }

    /* Simple reset */
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
        margin:0;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        background: linear-gradient(180deg,var(--bg-1),var(--bg-2));
        color:var(--text);
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
        overflow-x:hidden;
    }

    /* Canvas background */
    #skyCanvas{
        position:fixed;
        inset:0;
        z-index:0;
        display:block;
    }

    /* Decorative shapes */
    .moon {
        position:fixed;
        left:6%;
        top:8%;
        width:120px;
        height:120px;
        border-radius:50%;
        background: radial-gradient(circle at 35% 30%, #f8f9ff, #dbe7ff 20%, rgba(255,255,255,0.04) 60%, transparent 70%);
        box-shadow: 0 0 40px rgba(200,220,255,0.08), 0 6px 24px rgba(0,0,0,0.4) inset;
        z-index:1;
        transform-origin:center;
        animation: moonFloat 9s ease-in-out infinite;
        pointer-events:none;
    }
    @keyframes moonFloat{
        0%{transform: translateY(0) translateX(0)}
        50%{transform: translateY(12px) translateX(6px)}
        100%{transform: translateY(0) translateX(0)}
    }

    .planet {
        position:fixed;
        right:6%;
        bottom:6%;
        width:220px;
        height:220px;
        border-radius:50%;
        background: radial-gradient(circle at 40% 35%, rgba(123,70,255,0.24), rgba(255,122,162,0.12) 35%, transparent 60%);
        filter: blur(18px);
        z-index:1;
        animation: planetPulse 7s ease-in-out infinite;
        pointer-events:none;
    }
    @keyframes planetPulse{
        0%{transform: scale(1)}
        50%{transform: scale(1.14)}
        100%{transform: scale(1)}
    }

    /* Main layout */
    .page {
        position:relative;
        z-index:2;
        min-height:100vh;
        display:flex;
        flex-direction:column;
        align-items:center;
        padding:36px 20px;
    }

    header {
        width:100%;
        max-width:1100px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }

    .brand {
        display:flex;
        gap:12px;
        align-items:center;
    }

    .logo {
        width:56px;height:56px;border-radius:12px;
        background:linear-gradient(135deg,var(--accent-1),var(--accent-2));
        display:grid;place-items:center;
        box-shadow: 0 6px 18px rgba(123,70,255,0.16);
        font-weight:700;
        color:white;
        font-size:20px;
    }

    .brand h1{
        font-size:18px;
        margin:0;
        color:var(--text);
        line-height:1;
    }

    .brand p{margin:0;color:var(--muted);font-size:13px}

    .nav {
        display:flex;
        gap:12px;
        align-items:center;
    }

    a.btn {
        display:inline-flex;
        gap:8px;
        align-items:center;
        padding:10px 16px;
        border-radius:10px;
        text-decoration:none;
        color:var(--text);
        background:linear-gradient(90deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        border:1px solid rgba(255,255,255,0.04);
        transition: transform .18s ease, box-shadow .18s ease;
        font-size:14px;
    }
    a.btn:hover{ transform: translateY(-3px); box-shadow:0 10px 30px rgba(0,0,0,0.5) }

    a.cta{
        background: linear-gradient(90deg,var(--accent-1),var(--accent-2));
        color:white;
        border:none;
        font-weight:600;
        box-shadow: 0 8px 28px rgba(123,70,255,0.18);
    }

    /* Floating dashboard icon (when logged in) */
    .dash-icon{
        position:fixed;
        right:20px; top:20px;
        z-index:6;
        text-decoration:none;
        padding:12px 16px;border-radius:999px;
        background:rgba(255,255,255,0.06);
        color:var(--text);
        backdrop-filter: blur(6px);
        border:1px solid rgba(255,255,255,0.04);
        transition: transform .18s;
    }
    .dash-icon:hover{ transform: scale(1.06) }

    /* Hero */
    .hero {
        width:100%;
        max-width:1100px;
        margin:38px auto;
        display:grid;
        grid-template-columns: 1fr 420px;
        gap:28px;
        align-items:center;
    }

    /* Left content */
    .hero-left {
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        padding:28px;
        border-radius:var(--card-radius);
        border:1px solid rgba(255,255,255,0.04);
        box-shadow: 0 12px 40px rgba(2,6,23,0.6);
        backdrop-filter: blur(6px);
        animation: appearUp .9s ease both;
    }

    @keyframes appearUp {
        from { opacity:0; transform: translateY(22px) }
        to { opacity:1; transform: translateY(0) }
    }

    .hero-left h2{
        margin:0 0 12px 0;
        font-size:28px;
        color:var(--text);
        line-height:1.05;
    }

    .hero-left p{ color:var(--muted); margin:0 0 18px 0; font-size:15px }

    .features { display:flex; flex-direction:column; gap:10px; margin-top:6px }
    .feature {
        display:flex; gap:12px; align-items:flex-start;
    }
    .feature .icon {
        width:42px;height:42px;border-radius:10px;
        background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        display:grid;place-items:center;color:var(--success);
        border:1px solid rgba(255,255,255,0.03);
        font-weight:700;
        flex-shrink:0;
    }
    .feature .text { color:var(--muted); font-size:14px }

    /* Right card - controls */
    .hero-right {
        padding:20px;
        border-radius:14px;
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border:1px solid rgba(255,255,255,0.04);
        backdrop-filter: blur(6px);
        display:flex;
        flex-direction:column;
        gap:12px;
        align-items:stretch;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        animation: appearUp .9s ease .08s both;
    }

    .hero-right .title { font-weight:700; font-size:18px; color:var(--text) }
    .hero-right p{ margin:0; color:var(--muted); font-size:14px }

    .controls { display:flex; gap:12px; flex-wrap:wrap; margin-top:12px }
    button.btn {
        border:0;padding:10px 14px;border-radius:10px; cursor:pointer;
        background:linear-gradient(90deg,var(--accent-1),var(--accent-2));
        color:white;font-weight:700;box-shadow:0 8px 26px rgba(123,70,255,0.16);
        transition: transform .14s ease;
    }
    button.btn.secondary {
        background:transparent;color:var(--text);
        border:1px solid rgba(255,255,255,0.06);
        box-shadow:none;font-weight:600;
    }
    button.btn:active{ transform: translateY(2px) }

    /* Instruction modal */
    .modal-backdrop {
        position:fixed; inset:0; display:none; place-items:center;
        background: rgba(3,6,16,0.6); z-index:20;
    }
    .modal {
        width:94%; max-width:760px; background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border-radius:12px;padding:18px; border:1px solid rgba(255,255,255,0.04);
        box-shadow: 0 16px 50px rgba(2,6,23,0.7);
        color:var(--text);
    }
    .modal h3{ margin:0 0 8px 0}
    .steps { margin-top:10px; display:flex; flex-direction:column; gap:10px; color:var(--muted) }

    /* Footer */
    footer {
        margin-top:auto;
        padding:18px;
        width:100%;
        max-width:1100px;
        color:var(--muted);
        font-size:13px;
        display:flex;
        justify-content:space-between;
    }

    /* small screens */
    @media (max-width:880px){
        .hero { grid-template-columns: 1fr; }
        .moon { display:none }
        .planet { display:none }
    }
</style>
</head>
<body>
<!-- Canvas for animated sky (stars, shooting stars, nebula, particles) -->
<canvas id="skyCanvas" aria-hidden="true"></canvas>

<!-- Decorative shapes -->
<div class="moon" aria-hidden="true"></div>
<div class="planet" aria-hidden="true"></div>

<div class="page" role="main">

    <!-- Header with brand and nav -->
    <header>
        <div class="brand" aria-hidden="false">
            <div class="logo" aria-hidden="true">RW</div>
            <div>
                <h1>RestWell</h1>
                <p>Sleep intelligence & guided wellness</p>
            </div>
        </div>

        <nav class="nav" aria-label="Main Navigation">
            <a href="register.php" class="btn" role="button">Register</a>
            <a href="login.php" class="btn" role="button">Login</a>
            <a href="about.php" class="btn" role="button">About</a>
        </nav>
    </header>

    <!-- Dashboard quick link when logged in -->
    <?php if (isset($_SESSION['user'])): ?>
        <a href="dashboard.php" class="dash-icon" aria-label="Open dashboard">🏠 Dashboard</a>
    <?php endif; ?>

    <!-- Hero area -->
    <section class="hero" aria-labelledby="heroTitle">
        <div class="hero-left">
            <h2 id="heroTitle">Welcome to RestWell</h2>
            <p>Sleep shapes your day. RestWell combines gentle guidance, personalized insights, and practical tools to help you sleep better and live brighter.</p>

            <div class="features" aria-hidden="false">
                <div class="feature">
                    <div class="icon">S</div>
                    <div class="text">Smart sleep monitoring with AI-driven insights</div>
                </div>
                <div class="feature">
                    <div class="icon">E</div>
                    <div class="text">Book certified experts & guided wellness sessions</div>
                </div>
                <div class="feature">
                    <div class="icon">R</div>
                    <div class="text">Personalized relaxation & exercise plans</div>
                </div>
            </div>
        </div>

        <aside class="hero-right" aria-labelledby="controlTitle">
            <div>
                <div class="title" id="controlTitle">Quick actions</div>
                <p>Start a short guided relaxation, view step-by-step instructions, or explore your dashboard.</p>
            </div>

            <div class="controls" role="group" aria-label="Quick actions">
                <button class="btn" id="startRelax">▶ Start Muscle Relaxation</button>
                <button class="btn secondary" id="viewInstr">📖 View Instructions</button>
                <button class="btn secondary" id="openTips">💡 Tips</button>
            </div>

            <div id="relaxStatus" style="margin-top:12px;color:var(--muted);font-size:14px">
                Not running
            </div>
        </aside>
    </section>

    <footer>
        <div>© <?php echo date("Y"); ?> RestWell — Built with care</div>
        <div>Made for restful nights • Accessible • Private</div>
    </footer>
</div>

<!-- Modal for instructions -->
<div class="modal-backdrop" id="modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal" role="document">
        <button id="closeModal" aria-label="Close" style="float:right;border:0;background:transparent;color:var(--muted);font-size:18px;cursor:pointer">✕</button>
        <h3>Muscle Relaxation — Step-by-step</h3>
        <div class="steps" id="stepsList">
            <div>1. Lie down comfortably and close your eyes.</div>
            <div>2. Take 3 slow deep breaths: inhale 4s, hold 2s, exhale 6s.</div>
            <div>3. Tense each muscle group for 5 seconds, then relax for 10 seconds — start from toes and move up.</div>
            <div>4. Focus on relaxing sensations; repeat until calm.</div>
            <div>5. Finish with a gentle breath and slowly open your eyes.</div>
        </div>
    </div>
</div>

<script>
/* ============================
   Animated sky: canvas-based
   - twinkling stars
   - shooting star bursts
   - moving nebula-like gradient
   - floating particles
   ============================ */

(function(){
    const canvas = document.getElementById('skyCanvas');
    const ctx = canvas.getContext('2d', { alpha: true });

    let W = canvas.width = innerWidth;
    let H = canvas.height = innerHeight;
    let stars = [];
    let particles = [];
    let shootingStars = [];

    // Helpers
    function rand(min, max){ return Math.random() * (max - min) + min; }

    // Create twinkling stars
    const STAR_COUNT = Math.min(160, Math.floor((W*H)/6000)); // responsive density
    for (let i=0;i<STAR_COUNT;i++){
        stars.push({
            x: Math.random()*W,
            y: Math.random()*H,
            r: rand(0.4,1.8),
            alpha: rand(0.2,0.95),
            twinkleRate: rand(0.002,0.01),
            phase: Math.random()*Math.PI*2
        });
    }

    // Particles (slow floating)
    const P_COUNT = Math.min(60, Math.floor((W*H)/20000));
    for(let i=0;i<P_COUNT;i++){
        particles.push({
            x: Math.random()*W,
            y: Math.random()*H,
            vx: rand(-0.1,0.1),
            vy: rand(-0.03,0.03),
            size: rand(1.2,3.2),
            alpha: rand(0.02,0.12)
        });
    }

    // Shooting star spawner
    function spawnShootingStar(){
        // start at random top-left quadrant, move diagonally down-right
        const sx = rand(-W*0.1, W*0.6);
        const sy = rand(-H*0.1, H*0.4);
        shootingStars.push({
            x: sx,
            y: sy,
            len: rand(120, 420),
            speed: rand(8, 18) * (Math.max(W,H)/1200),
            life: 0,
            maxLife: rand(60,120),
            angle: Math.PI*0.85 // slightly down-right
        });
    }

    // ensure at least a few shooting stars keep appearing
    let shootTimer = 0;
    let nextShootAt = rand(80,200);

    // Nebula gradient offset
    let nebulaOffset = 0;

    function resize(){
        W = canvas.width = innerWidth;
        H = canvas.height = innerHeight;
    }
    addEventListener('resize', resize);

    // Mouse interactive twinkle (subtle)
    let mouse = {x:-9999,y:-9999};
    addEventListener('mousemove', (e)=>{ mouse.x=e.clientX; mouse.y=e.clientY });

    function draw(){
        ctx.clearRect(0,0,W,H);

        // subtle moving nebula (radial gradients)
        nebulaOffset += 0.002;
        const g1 = ctx.createRadialGradient(W*0.15, H*0.2, 50, W*0.2, H*0.25, Math.max(W,H)*0.9);
        g1.addColorStop(0, 'rgba(123,70,255,0.10)');
        g1.addColorStop(0.35, 'rgba(123,70,255,0.04)');
        g1.addColorStop(1, 'rgba(12,13,35,0)');
        ctx.fillStyle = g1;
        ctx.fillRect(0,0,W,H);

        const g2 = ctx.createRadialGradient(W*0.85, H*0.75, 50, W*0.8, H*0.85, Math.max(W,H)*0.9);
        g2.addColorStop(0, 'rgba(255,122,162,0.08)');
        g2.addColorStop(0.35, 'rgba(255,122,162,0.02)');
        g2.addColorStop(1, 'rgba(10,12,30,0)');
        ctx.fillStyle = g2;
        ctx.fillRect(0,0,W,H);

        // draw particles
        for(let p of particles){
            p.x += p.vx;
            p.y += p.vy;
            if(p.x < -20) p.x = W + 20;
            if(p.x > W + 20) p.x = -20;
            if(p.y < -20) p.y = H + 20;
            if(p.y > H + 20) p.y = -20;

            ctx.beginPath();
            ctx.fillStyle = `rgba(255,255,255,${p.alpha})`;
            ctx.arc(p.x, p.y, p.size, 0, Math.PI*2);
            ctx.fill();
        }

        // draw stars with twinkle & mouse proximity
        for(let s of stars){
            s.phase += s.twinkleRate;
            const tw = (Math.sin(s.phase) + 1) / 2; // 0..1
            const prox = Math.max(0, 1 - (Math.hypot(s.x-mouse.x, s.y-mouse.y) / 260));
            const opacity = Math.min(1, s.alpha * (0.6 + tw*0.8) + prox*0.8);
            ctx.beginPath();
            const r = s.r * (0.85 + tw*1.2 + prox*0.7);
            ctx.fillStyle = `rgba(255,255,255,${opacity})`;
            ctx.arc(s.x, s.y, r, 0, Math.PI*2);
            ctx.fill();
        }

        // draw and update shooting stars
        for(let i = shootingStars.length-1;i>=0;i--){
            let st = shootingStars[i];
            st.life++;
            // position along ray
            const dx = Math.cos(st.angle) * st.speed;
            const dy = Math.sin(st.angle) * st.speed;
            st.x += dx;
            st.y += dy;

            // bright core (head)
            const headX = st.x;
            const headY = st.y;
            ctx.save();
            ctx.beginPath();
            const grad = ctx.createLinearGradient(headX, headY, headX - dx*st.len*0.9, headY - dy*st.len*0.9);
            grad.addColorStop(0, 'rgba(255,255,255,1)');
            grad.addColorStop(0.2, 'rgba(255,255,255,0.8)');
            grad.addColorStop(0.5, 'rgba(255,255,255,0.35)');
            grad.addColorStop(1, 'rgba(255,255,255,0)');
            ctx.strokeStyle = grad;
            ctx.lineWidth = Math.max(1.2, st.len/200);
            ctx.lineCap = 'round';
            ctx.moveTo(headX, headY);
            ctx.lineTo(headX - Math.cos(st.angle)*st.len, headY - Math.sin(st.angle)*st.len);
            ctx.stroke();
            ctx.restore();

            // small glow
            ctx.beginPath();
            ctx.fillStyle = 'rgba(255,255,255,0.9)';
            ctx.arc(headX, headY, 2.2, 0, Math.PI*2);
            ctx.fill();

            if(st.life > st.maxLife || st.x > W + 200 || st.y > H + 200) {
                shootingStars.splice(i,1);
            }
        }

        // spawn shooting stars occasionally
        shootTimer++;
        if(shootTimer > nextShootAt){
            spawnShootingStar();
            // occasionally spawn quick bursts
            if(Math.random() < 0.18) spawnShootingStar();
            nextShootAt = rand(60,240);
            shootTimer = 0;
        }

        // subtle vignette to frame content
        const vignette = ctx.createRadialGradient(W/2, H/2, Math.min(W,H)*0.2, W/2, H/2, Math.max(W,H));
        vignette.addColorStop(0, 'rgba(0,0,0,0)');
        vignette.addColorStop(0.6, 'rgba(0,0,0,0.06)');
        vignette.addColorStop(1, 'rgba(0,0,0,0.26)');
        ctx.fillStyle = vignette;
        ctx.fillRect(0,0,W,H);

        // request next frame
        requestAnimationFrame(draw);
    }

    // spawn the first shooting stars
    for(let i=0;i<3;i++) spawnShootingStar();

    // start loop
    draw();

    // simple helper to push a shooting star from UI interactions (optional)
    window.triggerShootingStar = function(){
        spawnShootingStar();
    };

})();

/* ============================
   UI behaviours: modal + relaxation flow
   ============================ */

(function(){
    const modal = document.getElementById('modal');
    const viewBtn = document.getElementById('viewInstr');
    const closeBtn = document.getElementById('closeModal');
    const startBtn = document.getElementById('startRelax');
    const tipsBtn = document.getElementById('openTips');
    const relaxStatus = document.getElementById('relaxStatus');

    function showModal(){
        modal.style.display = 'grid';
        modal.setAttribute('aria-hidden','false');
        // accessibility: focus trap - focus first actionable element
        closeBtn.focus();
    }
    function hideModal(){
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden','true');
    }
    viewBtn.addEventListener('click', showModal);
    closeBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', (e)=>{
        if(e.target === modal) hideModal(); // close on backdrop click
    });
    document.addEventListener('keydown', (e)=>{
        if(e.key === 'Escape') hideModal();
    });

    // simple breathing / progressive muscle relaxation routine:
    // sequence of steps with durations (seconds)
    const sequence = [
        { text: 'Take slow deep breaths: inhale 4s, hold 2s, exhale 6s', duration: 14 },
        { text: 'Tense toes & feet for 5s, then relax for 10s', duration: 15 },
        { text: 'Tense calves & thighs for 5s, then relax for 10s', duration: 15 },
        { text: 'Tense abdomen & chest for 5s, then relax for 10s', duration: 15 },
        { text: 'Tense hands & arms for 5s, then relax for 10s', duration: 15 },
        { text: 'Tense shoulders & neck for 5s, then relax for 10s', duration: 15 },
        { text: 'Finish: long slow breath, relax, open your eyes gently.', duration: 10 }
    ];

    let running = false;
    let seqTimer = null;

    function displayProgress(text, remaining){
        relaxStatus.textContent = text + (remaining ? ` — ${remaining}s` : '');
    }

    function runSequence(){
        if(running) return;
        running = true;
        let i = 0;
        displayProgress('Starting relaxation...');
        // also trigger a visible shooting star as a cute effect
        if(window.triggerShootingStar) window.triggerShootingStar();

        function nextStep(){
            if(i >= sequence.length){
                displayProgress('Relaxation complete — well done!');
                running = false;
                return;
            }
            const step = sequence[i];
            let remaining = step.duration;
            displayProgress(step.text, remaining);
            seqTimer = setInterval(()=>{
                remaining--;
                displayProgress(step.text, remaining);
                if(remaining <= 0) {
                    clearInterval(seqTimer);
                    i++;
                    // small visual cue
                    if(window.triggerShootingStar) window.triggerShootingStar();
                    setTimeout(nextStep, 600);
                }
            },1000);
        }
        nextStep();
    }

    startBtn.addEventListener('click', runSequence);

    // small tips modal - reuse modal element
    tipsBtn.addEventListener('click', ()=>{
        // replace steps content quickly
        const stepsList = document.getElementById('stepsList');
        stepsList.innerHTML = `
            <div><strong>Tip 1:</strong> Keep your bedroom cool and dark.</div>
            <div><strong>Tip 2:</strong> Avoid screens 30 minutes before bed.</div>
            <div><strong>Tip 3:</strong> Try consistent sleep-wake times.</div>
            <div><strong>Tip 4:</strong> Short relaxation before bed improves sleep onset.</div>
        `;
        showModal();
    });

})();

/* small enhancement: clicking hero CTA triggers a visible shooting star and smooth scroll */
document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', function(e){
        const target = document.querySelector(this.getAttribute('href'));
        if(target){
            e.preventDefault();
            target.scrollIntoView({behavior:'smooth', block:'center'});
        }
        // visual flourish
        if(window.triggerShootingStar) window.triggerShootingStar();
    })
});
</script>
</body>
</html>
