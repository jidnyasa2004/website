<?php
session_start();
ob_start(); // Start Output Buffering
include 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Store customer name in session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_name'])) {
    $_SESSION['customer_name'] = $_POST['customer_name'];
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Cash on Delivery Order Placement
if (isset($_POST['cod_order'])) {
    if (empty($_SESSION['customer_name'])) {
        echo "<script>alert('Please enter your name before proceeding!'); window.location.href='cart.php';</script>";
        exit;
    }

    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
        exit;
    }

    $conn->begin_transaction();
    try {
        $u_id = $_SESSION['user_id'] ?? null;
        $customer_name = $_SESSION['customer_name'];
        $date_time = date('Y-m-d H:i:s');
        $total_amount = 0;
        $no_of_items = count($_SESSION['cart']);

        // Calculate total amount
        foreach ($_SESSION['cart'] as &$item) {
            if (!isset($item['quantity'])) {
                $item['quantity'] = 1; // Default quantity if missing
            }
            $quantity = $item['quantity'];
            $total_amount += $item['price'] * $quantity;
        }
        unset($item); // Break reference

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, order_date, total_amount, no_of_items, date_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $customer_name, $date_time, $total_amount, $no_of_items, $date_time);
        if (!$stmt->execute()) {
            throw new Exception("Orders Insertion Failed: " . $stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert into payments table (Cash on Delivery)
        $payment_status = "pending";
        $payment_mode = "offline";
        $transaction_id = null;

        $stmt = $conn->prepare("INSERT INTO payments (u_id, order_id, payment_status, payment_date, transaction_id, payment_mode, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssd", $u_id, $order_id, $payment_status, $date_time, $transaction_id, $payment_mode, $total_amount);
        if (!$stmt->execute()) {
            throw new Exception("Payments Insertion Failed: " . $stmt->error);
        }
        $stmt->close();

        $conn->commit();
        unset($_SESSION['cart']); // Clear cart

        // ✅ Directly Redirect to Invoice Page
        header("Location: invoice.php?order_id=$order_id");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='cart.php';</script>";
        exit;
    }
}
ob_end_flush(); // Flush Output Buffer
?>
