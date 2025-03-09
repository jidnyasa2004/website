<?php
session_start();
include 'connection.php'; // Include database connection

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Order ID is missing!";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    echo "Invalid Order ID!";
    exit();
}

$c_id = $order['c_id'];

// Fetch customer details
$customer_query = "SELECT * FROM customer WHERE c_id = '$c_id'";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);

// Fetch order items
$order_items_query = "SELECT oi.*, m.name FROM order_items oi
                      JOIN medicine m ON oi.med_id = m.med_id
                      WHERE oi.order_id = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);

// Fetch payment details
$payment_query = "SELECT * FROM payment WHERE order_id = '$order_id'";
$payment_result = mysqli_query($conn, $payment_query);
$payment = mysqli_fetch_assoc($payment_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Patil Medical Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .invoice-container {
            width: 60%;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            font-size: 26px;
            margin-bottom: 5px;
        }
        .store-info {
            color: #555;
            margin-bottom: 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        .total {
            font-weight: bold;
            background-color: #ecf0f1;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        button {
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        .print-btn {
            background-color: #27ae60;
            color: white;
        }
        .print-btn:hover {
            background-color: #219150;
        }
        .home-btn {
            background-color: #e74c3c;
            color: white;
        }
        .home-btn:hover {
            background-color: #c0392b;
        }
        .payment-info {
            background: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <h1>Patil Medical Store</h1>
    <p class="store-info"><strong>Address:</strong> Mulund | <strong>Phone:</strong> 9000011111 | <strong>Email:</strong> medicalstore@gmail.com</p>

    <div class="invoice-header">
        <div>
            <p><strong>Invoice No:</strong> <?= $order['order_id'] ?></p>
            <p><strong>Date:</strong> <?= $order['date_time'] ?></p>
        </div>
        <div>
            <p><strong>Customer:</strong> <?= $customer['name'] ?></p>
            <p><strong>Email:</strong> <?= $customer['email'] ?></p>
        </div>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
                <th>Total (₹)</th>
            </tr>
            <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'] / $item['quantity'], 2) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td colspan="3" align="right">Total Amount:</td>
                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <p><strong>Payment Mode:</strong> <?= $payment['payment_mode'] ?></p>
        <p><strong>Payment Status:</strong> <?= $payment['payment_status'] ?></p>
    </div>

    <div class="buttons">
        <button class="print-btn" onclick="window.print()">Print Invoice</button>
        <a href="homenew.php">
            <button class="home-btn">Back to Home</button>
        </a>
    </div>
</div>

</body>
</html>
