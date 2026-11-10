<?php
require_once __DIR__ . '/config.php';

$host   = getEnvValue('DB_HOST', 'localhost');
$user   = getEnvValue('DB_USER', 'root');
$pass   = getEnvValue('DB_PASS', '');
$dbname = getEnvValue('DB_NAME', 'restwell');
$port   = (int)getEnvValue('DB_PORT', 3306);

try {
    if ($port > 0) {
        try {
            $conn = new mysqli($host, $user, $pass, $dbname, $port);
        } catch (Exception $portEx) {
            // If custom port fails (e.g. 3307 not open), fallback to standard port 3306
            if ($port !== 3306) {
                $conn = new mysqli($host, $user, $pass, $dbname, 3306);
            } else {
                throw $portEx;
            }
        }
    } else {
        $conn = new mysqli($host, $user, $pass, $dbname);
    }

    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
