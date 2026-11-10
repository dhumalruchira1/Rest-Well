<?php 
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>About RestWell - Sleep Cycle Care</title>

    <!-- Your style.css -->
    <link rel="stylesheet" href="css/style.css">

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

        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, sans-serif;
            background: #0b1726;
            color: white;
            overflow-x: hidden;
        }

        .night-bg {
            position: fixed;
            top: 0 !important;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(#00111a, #000);
            z-index: -1;
        }

        .page-title {
            margin-top: 40px;
            font-size: 40px;
            font-weight: bold;
            text-align: center;
            text-shadow: 0 0 12px rgba(255,255,255,0.6);
        }

        .sub-title {
            text-align: center;
            margin-top: -10px;
            font-size: 18px;
            opacity: 0.8;
        }

        .menu-bar {
            margin: 30px auto;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .menu-btn {
            padding: 10px 25px;
            background: linear-gradient(45deg, #3f0a6b, #ff758c);
            border-radius: 8px;
            color: white;
            font-size: 17px;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu-btn:hover {
            transform: scale(1.07);
            box-shadow: 0 0 12px pink;
        }

        .about-box {
            width: 85%;
            max-width: 900px;
            margin: 25px auto;
            padding: 25px;
            background: rgba(255,255,255,0.07);
            border-radius: 12px;
            backdrop-filter: blur(6px);
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
            line-height: 1.7;
        }

        .about-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .about-box p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .dash-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.12);
            padding: 12px 20px;
            border-radius: 40px;
            color: #fff;
            font-size: 19px;
            text-decoration: none;
            font-weight: bold;
            backdrop-filter: blur(6px);
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
            transition: 0.3s;
        }

        .dash-icon:hover {
            transform: scale(1.12);
            background: rgba(125,211,252,0.18);
            box-shadow: 0 0 20px #7dd3fc;
        }
    </style>
</head>

<body>

<?php if(isset($_SESSION['user'])): ?>
    <a href="dashboard.php" class="dash-icon">🏠 Dashboard</a>
<?php endif; ?>

<!-- Background -->
<div class="night-bg">
    <div class="stars"></div>
    <div class="clouds"></div>
</div>

<div class="home-container" style="text-align:center; margin-top:50px;">
    <div class="page-title">About RestWell</div>
    <div class="sub-title">Smart Sleep Cycle & Health Wellness System</div>

    <div class="menu-bar">
        <a class="menu-btn" href="index.php">Home</a>
        <a class="menu-btn" href="register.php">Register</a>
        <a class="menu-btn" href="login.php">Login</a>
    </div>

    <div class="about-box">
        <h2>Welcome to RestWell 🌙</h2>
        <p>
            In today’s fast-paced digital world, modern lifestyles have significantly affected people’s physical and mental well-being. 
            Among the various factors influencing health, sleep quality has emerged as one of the most accurate indicators of a person’s 
            overall physical, emotional, and cognitive health. Poor sleep patterns can lead to stress, fatigue, low immunity, decreased 
            productivity, and several long-term health issues. While many people rely on general fitness applications, most of these 
            platforms offer limited insights, lack personalized recommendations, and do not integrate advanced analytics with professional 
            consultation support.
        </p>
        <p>
            RestWell is an innovative web-based platform designed to address these challenges by offering a complete ecosystem for 
            sleep-based health monitoring and wellness management. It functions as a smart digital companion that allows users to 
            understand their sleep cycle, track health-related parameters, and receive intelligent suggestions to improve their daily lifestyle. 
            The platform goes beyond simple sleep tracking by integrating AI-powered analysis, exercise recommendations, expert consultation, 
            and health education, making it a comprehensive solution for users seeking to maintain or improve their well-being.
        </p>
        <p>
            RestWell provides a user-friendly interface with clearly separated web pages for different functionalities, ensuring smooth 
            navigation and an organized experience for users. Each feature—such as registration, login, sleep data monitoring, 
            consultation booking, payment, feedback, and health tips—is presented on its own dedicated page to maintain clarity and 
            reduce user confusion. The system offers essential capabilities like email notifications, SMS updates, booking confirmations, 
            and downloadable receipts, ensuring transparency and convenience for users at every step.
        </p>
        <p>
            A unique highlight of RestWell is its AI-driven recommendation engine, which analyzes the user's sleep patterns and lifestyle 
            inputs to provide personalized exercise routines, wellness suggestions, and preventive health insights. The platform also offers 
            access to detailed information about health experts, enabling users to choose consultants based on their needs and preferences. 
            Additional features such as a health tips section, book purchasing, and feedback module further contribute to the system’s value, 
            helping users stay motivated, informed, and engaged in their health journey.
        </p>
        <p>
            Overall, RestWell aims to serve as a powerful digital wellness assistant that empowers users to understand their health more 
            deeply, make informed decisions, and adopt healthier sleeping habits. By combining sleep science, modern technology, and expert 
            support into one unified system, RestWell becomes a reliable tool for individuals who want to improve their lifestyle and 
            achieve long-term well-being.
        </p>
    </div>
</div>

</body>
</html>
