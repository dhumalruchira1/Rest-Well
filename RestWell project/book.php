<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RestWell Health Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: Arial,sans-serif;
    background: #0b1726;
    color: #fff;
    margin:0; padding:0;
}
header {
    text-align: center;
    padding: 30px 0;
    font-size: 28px;
    color:#7dd3fc;
    text-shadow:0 0 10px rgba(125,211,252,0.5);
}
nav {
    text-align:center;
    margin-bottom:30px;
}
nav a {
    color:#9fd8ff;
    text-decoration:none;
    font-weight:700;
    margin:0 15px;
}
.book-container {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
    width:90%;
    max-width:1200px;
    margin:20px auto;
}
.book {
    background: rgba(255,255,255,0.08);
    padding:15px;
    border-radius:12px;
    text-align:center;
    backdrop-filter: blur(8px);
    box-shadow:0 0 20px rgba(0,0,0,0.4);
    transition: transform 0.3s, box-shadow 0.3s;
}
.book:hover { transform:scale(1.05); box-shadow:0 0 20px #7dd3fc; }
.book img {
    width:100%;
    height:250px;
    object-fit:cover;
    border-radius:8px;
    margin-bottom:10px;
}
.book h3 { margin:10px 0; color:#7dd3fc; }
.book p { opacity:0.9; font-size:14px; }
.buy-box {
    display:block;
    width:100%;
    text-align:center;
    padding:10px 0;
    margin-top:12px;
    background:linear-gradient(90deg,#73b3ff,#7dd3fc);
    color:#052936;
    font-weight:bold;
    border-radius:8px;
    text-decoration:none;
    transition:0.3s;
}
.buy-box:hover { background:#42a5f5; }
.btn-audio-page, .btn-dashboard {
    display:block;
    width:250px;
    margin:20px auto;
    padding:12px;
    font-weight:700;
    color:#052936;
    text-decoration:none;
    border-radius:8px;
    text-align:center;
    background: linear-gradient(90deg,#73b3ff,#7dd3fc);
    transition:0.3s;
}
.btn-audio-page:hover, .btn-dashboard:hover { background:#42a5f5; }
</style>
</head>
<body>

<header>🌙 RestWell – Health Books</header>
<nav>
    <a href="index.php">Home</a>
    <a href="exercise.php">Exercises</a>
    <a href="health.php">Health Books</a>
</nav>

<div class="book-container"><!-- Book 1 -->
<div class="book">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4wox_3gVnnT1Kw5I5raeKY4yEaEwyexwnAA&s">
    <h3>Sleep Improvement Guide</h3>
    <p>Price: ₹120</p>
    <a href="checkout.php?book=Sleep Improvement Guide&price=120" class="buy-box">Buy Now</a>
</div>

<!-- Book 2 -->
<div class="book">
    <img src="https://ih1.redbubble.net/image.1122061968.2837/sn,x1000-pad,750x1000,f8f8f8.jpg">
    <h3>Healthy Mind Notebook</h3>
    <p>Price: ₹140</p>
    <a href="checkout.php?book=Healthy Mind Notebook&price=140" class="buy-box">Buy Now</a>
</div>

<!-- Book 3 -->
<div class="book">
    <img src="https://cdn.shopify.com/s/files/1/1054/4168/files/tips_to_get_healthy_deep_sleep.jpg?v=1641298914">
    <h3>Deep Sleep Routine</h3>
    <p>Price: ₹100</p>
    <a href="checkout.php?book=Deep Sleep Routine&price=100" class="buy-box">Buy Now</a>
</div>

<!-- Book 4 -->
<div class="book">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4L5YsFrn1uB27CO46tQYULYmhy3qcFlRAwA&s">
    <h3>Stress Relief Techniques</h3>
    <p>Price: ₹150</p>
    <a href="checkout.php?book=Stress Relief Techniques&price=150" class="buy-box">Buy Now</a>
</div>

<!-- Book 5 -->
<div class="book">
    <img src="https://i.etsystatic.com/22035664/r/il/745989/6222947586/il_340x270.6222947586_jvbl.jpg">
    <h3>Holistic Health Notes</h3>
    <p>Price: ₹130</p>
    <a href="checkout.php?book=Holistic Health Notes&price=130" class="buy-box">Buy Now</a>
</div>

<!-- Book 6 -->
<div class="book">
    <img src="https://smileindiatrust.org/wp-content/uploads/2025/01/WhatsApp-Image-2025-01-03-at-10.45.37-AM.jpeg">
    <h3>Body & Mind Wellness</h3>
    <p>Price: ₹150</p>
    <a href="checkout.php?book=Body & Mind Wellness&price=150" class="buy-box">Buy Now</a>
</div>

<!-- Book 7 -->
<div class="book">
    <img src="https://www.thehealthy.com/wp-content/uploads/2020/08/self-care-quote_14.jpg?fit=700%2C700">
    <h3>Yoga & Meditation Guide</h3>
    <p>Price: ₹160</p>
    <a href="checkout.php?book=Yoga & Meditation Guide&price=160" class="buy-box">Buy Now</a>
</div>

<!-- Book 8 -->
<div class="book">
    <img src="https://m.media-amazon.com/images/I/71QMiwEIpGL.jpg">
    <h3>Nutrition Essentials</h3>
    <p>Price: ₹140</p>
    <a href="checkout.php?book=Nutrition Essentials&price=140" class="buy-box">Buy Now</a>
</div>

<!-- Book 9 -->
<div class="book">
    <img src="https://m.media-amazon.com/images/I/8170ENzDR6L._UF1000,1000_QL80_.jpg">
    <h3>Healthy Habits Journal</h3>
    <p>Price: ₹110</p>
    <a href="checkout.php?book=Healthy Habits Journal&price=110" class="buy-box">Buy Now</a>
</div>

<!-- Book 10 -->
<div class="book">
    <img src="https://images.pexels.com/photos/4050316/pexels-photo-4050316.jpeg">
    <h3>Mindfulness Meditation</h3>
    <p>Price: ₹180</p>
    <a href="checkout.php?book=Mindfulness Meditation&price=180" class="buy-box">Buy Now</a>
</div>

<!-- Book 11 -->
<div class="book">
    <img src="https://images.pexels.com/photos/4050314/pexels-photo-4050314.jpeg">
    <h3>Sleep & Relaxation Techniques</h3>
    <p>Price: ₹170</p>
    <a href="checkout.php?book=Sleep & Relaxation Techniques&price=170" class="buy-box">Buy Now</a>
</div>

<!-- Book 12 -->
<div class="book">
    <img src="https://images.pexels.com/photos/4050315/pexels-photo-4050315.jpeg">
    <h3>Healthy Lifestyle Planner</h3>
    <p>Price: ₹200</p>
    <a href="checkout.php?book=Healthy Lifestyle Planner&price=200" class="buy-box">Buy Now</a>
</div>


</div>

<!-- Buttons -->
<a href="audio-page.html" class="btn-audio-page">Go to RestWell Audio Player</a>
<a href="dashboard.php" class="btn-dashboard">Back to Dashboard</a>

</body>
</html>
