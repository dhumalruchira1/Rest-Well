<?php
session_start();
require_once file_exists(__DIR__ . '/../src/db.php') ? __DIR__ . '/../src/db.php' : '../src/db.php';

$order_db_id = $_GET['order_id'] ?? '';
$payment_id = $_GET['payment_id'] ?? '';

if($order_db_id && $payment_id){
    $stmt = $conn->prepare("UPDATE orders SET payment_id=? WHERE id=?");
    $stmt->bind_param("si", $payment_id, $order_db_id);
    $stmt->execute();
    $stmt->close();
?>

<h2>✅ Payment Successful!</h2>
<p>Payment ID: <?= htmlspecialchars($payment_id) ?></p>
<p>Your order has been placed successfully.</p>
<a href="book.php">Back to Books</a>

<?php
} else {
    echo "<h2>❌ Invalid Payment</h2>";
    echo "<a href='checkout.php'>Go Back</a>";
}
?>
