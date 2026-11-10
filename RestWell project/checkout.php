<?php
session_start();
require_once file_exists(__DIR__ . '/src/db.php') ? __DIR__ . '/src/db.php' : '../src/db.php';

if(!isset($_SESSION['email'])){
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit;
}

$user_email = $_SESSION['email'];

// GET book & price
$book_name = $_GET['book'] ?? '';
$price = $_GET['price'] ?? '';

if(!$book_name || !$price){
    echo "<script>alert('Invalid book selection!'); window.location.href='book.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Checkout</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin:0; padding:0;}
        .container { width:60%; background:#fff; margin:30px auto; padding:25px; box-shadow:0 0 10px rgba(0,0,0,0.2); border-radius:10px;}
        h2 { text-align:center; color:#444;}
        input, textarea, select { width:100%; padding:10px; margin-top:10px; border-radius:5px; border:1px solid #aaa;}
        .btn { background:#ff9900; color:white; border:none; margin-top:20px; padding:15px; font-size:18px; width:100%; cursor:pointer; border-radius:5px;}
        .btn:hover { background:#e68a00;}
        .summary-box { background:#fafafa; padding:15px; border:1px solid #ddd; margin-bottom:20px; border-radius:5px;}
        .price { font-size:22px; font-weight:bold; color:green;}
        .section-title { margin-top:20px; font-size:20px; color:#222; border-bottom:2px solid #222; padding-bottom:5px;}
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>

    <div class="summary-box">
        <h3>📘 Book: <?= htmlspecialchars($book_name) ?></h3>
        <p class="price">Price: ₹<?= htmlspecialchars($price) ?></p>
    </div>

    <form action="book-payment-process.php" method="POST">
        <input type="hidden" name="book_name" value="<?= htmlspecialchars($book_name) ?>">
        <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">

        <div class="section-title">Shipping Address</div>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <textarea name="address" rows="3" placeholder="Full Address" required></textarea>
        <input type="text" name="pincode" placeholder="Pincode" required>

        <div class="section-title">Payment Method</div>
        <select name="payment_method" required>
            <option value="">Select Payment Method</option>
            <option value="COD">Cash on Delivery</option>
            <option value="UPI">UPI</option>
            <option value="Card">Credit/Debit Card</option>
        </select>

        <button type="submit" class="btn">Place Order</button>
    </form>
</div>

</body>
</html>
