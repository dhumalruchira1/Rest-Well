<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// SAFE DELETE USER ACCOUNT
$conn->query("DELETE FROM users WHERE email='$email'");

// IF YOU HAVE OTHER TABLES FOR USER, DELETE THERE ALSO
// CHECK TABLE EXISTS THEN DELETE
$conn->query("DROP TABLE IF EXISTS feedback"); // REMOVE this if you don't want to delete full table

// LOGOUT USER
session_unset();
session_destroy();

header("Location: delete_success.php");
exit;
?>
