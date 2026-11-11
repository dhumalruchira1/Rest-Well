<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

// Check if $conn is set and connected
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . ($conn->connect_error ?? "Connection not established"));
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/src/phpmailer/src/PHPMailer.php";
require_once __DIR__ . "/src/phpmailer/src/SMTP.php";
require_once __DIR__ . "/src/phpmailer/src/Exception.php";

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = trim($_POST['user_name']);
    $email = $_POST['email'];
    $expert_name = $_POST['expert_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Duplicate booking check
    $stmt = $conn->prepare("SELECT * FROM consult WHERE expert_name=? AND date=? AND time=?");
    if (!$stmt) {
        die("Prepare failed for duplicate check: " . $conn->error);
    }
    $stmt->bind_param("sss", $expert_name, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This expert is already booked at selected date and time. Please choose another slot.'); window.history.back();</script>";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Insert booking
    $stmt2 = $conn->prepare("INSERT INTO consult (user_name,email,expert_name,date,time) VALUES (?,?,?,?,?)");
    if (!$stmt2) {
        die("Prepare failed for insert: " . $conn->error);
    }
    $stmt2->bind_param("sssss", $user_name, $email, $expert_name, $date, $time);

    if ($stmt2->execute()) {
        // Send confirmation email (mobile-responsive as before)
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = getEnvValue('SMTP_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth = true;
            $mail->Username = getEnvValue('SMTP_USER', ''); // Your Gmail
            $mail->Password = getEnvValue('SMTP_PASS', '');   // Gmail App Password
            $mail->SMTPSecure = getEnvValue('SMTP_SECURE', 'tls');
            $mail->Port = (int)getEnvValue('SMTP_PORT', 587);

            $fromEmail = getEnvValue('SMTP_FROM_EMAIL', getEnvValue('SMTP_USER', ''));
            $fromName  = getEnvValue('SMTP_FROM_NAME', 'Restwell');
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($email, $user_name);

            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation - Restwell';

            // Mobile-responsive HTML email body
            $mail->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Appointment Confirmation</title>
                <style>
                    /* Reset and base styles */
                    body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
                    .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #ddd; }
                    .header { background-color: #0b1726; color: #7dd3fc; text-align: center; padding: 20px; }
                    .header h1 { margin: 0; font-size: 24px; }
                    .content { padding: 20px; }
                    .content h3 { color: #0b1726; font-size: 18px; margin-bottom: 10px; }
                    .content p { font-size: 16px; line-height: 1.5; margin: 10px 0; }
                    .appointment-details { background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin: 20px 0; }
                    .btn { display: inline-block; background-color: #7dd3fc; color: #052936; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; text-align: center; margin-top: 20px; }
                    .footer { text-align: center; padding: 20px; background-color: #0b1726; color: #9fd8ff; font-size: 14px; }
                    
                    /* Mobile responsiveness */
                    @media only screen and (max-width: 600px) {
                        .email-container { width: 100% !important; }
                        .header h1 { font-size: 20px !important; }
                        .content { padding: 15px !important; }
                        .content h3 { font-size: 16px !important; }
                        .content p { font-size: 14px !important; }
                        .appointment-details { padding: 10px !important; }
                        .btn { width: 100% !important; padding: 15px !important; }
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <h1>🌙 Restwell</h1>
                        <p>Appointment Confirmation</p>
                    </div>
                    <div class='content'>
                        <h3>Hi $user_name,</h3>
                        <p>Your appointment with <strong>$expert_name</strong> has been successfully booked!</p>
                        <div class='appointment-details'>
                            <p><strong>Date:</strong> $date</p>
                            <p><strong>Time:</strong> $time</p>
                            <p><strong>Expert:</strong> $expert_name</p>
                        </div>
                        <p>Thank you for choosing Restwell. We're excited to help you on your wellness journey!</p>
                        <a href='https://yourwebsite.com/dashboard.php' class='btn'>View Your Dashboard</a>
                    </div>
                    <div class='footer'>
                        <p>© 2025 Restwell. All rights reserved.</p>
                        <p>If you have any questions, reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }

        echo "<script>alert('Appointment booked successfully! Confirmation sent to your email.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Something went wrong! Please try again.'); window.history.back();</script>";
    }

    $stmt2->close();
    $conn->close();
} else {
    echo "<script>window.location.href='consult.php';</script>";
}
?>
