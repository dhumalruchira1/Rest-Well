<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if(!isset($_SESSION['email'])){
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $book = $_POST['book_name'];
    $price = $_POST['price'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $payment_method = $_POST['payment_method'];
    $user_email = $_SESSION['email'];

    // Generate dummy payment id
    $payment_id = 'DUMMY'.time();

    // Save order in DB
    $stmt = $conn->prepare("INSERT INTO orders (user_email, book_name, price, name, phone, address, pincode, payment_method) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssisssss", $user_email, $book, $price, $full_name, $phone, $address, $pincode, $payment_method);
    $stmt->execute();
    $orderDbId = $stmt->insert_id;
    $stmt->close();
?>

<h2>Dummy Payment</h2>
<p>Book: <?= htmlspecialchars($book) ?></p>
<p>Amount: ₹<?= htmlspecialchars($price) ?></p>
<p>Payment Method: <?= htmlspecialchars($payment_method) ?></p>

<form action="payment-sucess.php" method="GET">
    <input type="hidden" name="order_id" value="<?= $orderDbId ?>">
    <input type="hidden" name="payment_id" value="<?= $payment_id ?>">
    <button type="submit">Pay Now</button>
</form>

<?php
} // POST request close
?>
