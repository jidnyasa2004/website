<?php
session_start(); // ✅ Start session

// Check if customer name is set
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Guest';

// Check if cart exists in session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("<h2 style='color: red; text-align: center;'>Your cart is empty. Please add items first.</h2>");
}

// Generate a random order ID (You may replace this with actual database order ID)
$orderID = strtoupper(substr(md5(uniqid()), 0, 10)); // Example: A1B2C3D4E5

$totalAmount = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #141E30, #243B55);
            font-family: 'Poppins', sans-serif;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 700px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            text-align: center;
        }
        .invoice-header {
            font-size: 1.8rem;
            font-weight: bold;
            color: #FFD700;
            margin-bottom: 10px;
        }
        .invoice-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .invoice-item h2 {
            font-size: 1.2rem;
            color: #FFD700;
        }
        .invoice-item p {
            font-size: 1rem;
            color: #D1D5DB;
        }
        .invoice-item .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4ADE80;
        }
        .total {
            font-size: 1.4rem;
            font-weight: bold;
            text-align: right;
            color: #FFD700;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-block;
            background: #2563EB;
            color: white;
            padding: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
            transition: 0.3s ease;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #1E40AF;
        }
        .status {
            font-size: 1.2rem;
            color: #FACC15;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <h1 class="invoice-header">Invoice</h1>
    <p><strong>Order ID:</strong> <?= $orderID; ?></p> <!-- ✅ Display Order ID -->
    <p><strong>Customer Name:</strong> <?= htmlspecialchars($customerName); ?></p> <!-- ✅ Display Customer Name -->
    <p><strong>Date:</strong> <?= date("d M Y, h:i A"); ?></p>
    <p class="status">Order Status: Pending ⏳</p> <!-- ✅ Display Pending Status -->

    <hr style="margin: 10px 0; border-color: rgba(255, 255, 255, 0.5);">

    <?php foreach ($_SESSION['cart'] as $item): ?>
        <?php 
            $itemName = htmlspecialchars($item['name']);
            $itemPrice = htmlspecialchars($item['price']);
            $itemQuantity = isset($item['quantity']) ? (int) $item['quantity'] : 1; // ✅ Prevent "Undefined index" error
            $totalItemPrice = $itemPrice * $itemQuantity;
            $totalAmount += $totalItemPrice;
        ?>
        <div class="invoice-item">
            <div>
                <h2><?= $itemName; ?></h2>
                <p>Quantity: <?= $itemQuantity; ?></p> <!-- ✅ No more undefined error -->
            </div>
            <p class="price">₹<?= $totalItemPrice; ?></p>
        </div>
    <?php endforeach; ?>

    <div class="total">
        <p><strong>Total Amount:</strong> ₹<?= $totalAmount; ?></p>
    </div>

    <a href="homenew.php" class="back-btn">Back to Home page</a>
</div>

</body>
</html>
