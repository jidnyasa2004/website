<?php
session_start();
include 'connection.php'; // Database connection

if (!isset($_SESSION['u_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['u_id'];

// Fetch customer ID
$customer_query = "SELECT c_id FROM customer WHERE u_id = '$u_id'";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);
$c_id = $customer['c_id'] ?? null;

if (!$c_id) {
    header("Location: cart.php?error=customer_not_found");
    exit();
}

// Fetch cart items and calculate total amount & number of items
$cart_query = "SELECT * FROM cart WHERE u_id = '$u_id'";
$cart_result = mysqli_query($conn, $cart_query);

$total_amount = 0;
$total_items = 0;
$cart_items = [];

while ($cart = mysqli_fetch_assoc($cart_result)) {
    $med_id = $cart['med_id'];
    $quantity = $cart['quantity'];

    // Fetch medicine price
    $medicine_query = "SELECT price FROM medicine WHERE med_id = '$med_id'";
    $medicine_result = mysqli_query($conn, $medicine_query);
    $medicine = mysqli_fetch_assoc($medicine_result);
    $price = $medicine['price'];
    $total_price = $price * $quantity;

    $total_amount += $total_price;
    $total_items += $quantity;

    $cart_items[] = [
        'med_id' => $med_id,
        'quantity' => $quantity,
        'price' => $total_price
    ];
}

// If cart is empty, return to cart page
if (empty($cart_items)) {
    header("Location: cart.php?error=empty_cart");
    exit();
}

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // Generate Order ID
    $order_id_query = "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1";
    $order_id_result = mysqli_query($conn, $order_id_query);
    $last_order_id = mysqli_fetch_assoc($order_id_result)['order_id'] ?? null;

    $order_number = $last_order_id ? sprintf("O%05d", substr($last_order_id, 1) + 1) : "O00001";

    // Insert into orders table
    $insert_order = "INSERT INTO orders (order_id, total_amount, no_of_items, date_time, c_id, order_status)
                     VALUES ('$order_number', '$total_amount', '$total_items', NOW(), '$c_id', 'Pending')";
    if (!mysqli_query($conn, $insert_order)) {
        throw new Exception("Order Insert Failed: " . mysqli_error($conn));
    }

    // Insert into order_items table
    foreach ($cart_items as $item) {
        // Generate Order Item ID
        $order_item_id_query = "SELECT item_id FROM order_items ORDER BY item_id DESC LIMIT 1";
        $order_item_id_result = mysqli_query($conn, $order_item_id_query);
        $last_order_item_id = mysqli_fetch_assoc($order_item_id_result)['item_id'] ?? null;

        $order_item_number = $last_order_item_id ? sprintf("OI%05d", substr($last_order_item_id, 2) + 1) : "OI00001";

        $insert_order_items = "INSERT INTO order_items (item_id, order_id, med_id, quantity, price)
                               VALUES ('$order_item_number', '$order_number', '{$item['med_id']}', '{$item['quantity']}', '{$item['price']}')";
        if (!mysqli_query($conn, $insert_order_items)) {
            throw new Exception("Order Item Insert Failed: " . mysqli_error($conn));
        }
    }

    // Generate Payment ID
    $payment_id_query = "SELECT payment_id FROM payment ORDER BY payment_id DESC LIMIT 1";
    $payment_id_result = mysqli_query($conn, $payment_id_query);
    $last_payment_id = mysqli_fetch_assoc($payment_id_result)['payment_id'] ?? null;

    $payment_number = $last_payment_id ? sprintf("P%05d", substr($last_payment_id, 1) + 1) : "P00001";

    // Insert into payment table
    $insert_payment = "INSERT INTO payment (payment_id, order_id, amount, payment_mode, payment_status, payment_datetime, transaction_id)
                       VALUES ('$payment_number', '$order_number', '$total_amount', 'Offline', 'Pending', NOW(), NULL)";
    if (!mysqli_query($conn, $insert_payment)) {
        throw new Exception("Payment Insert Failed: " . mysqli_error($conn));
    }

    // Clear cart only after successful insertion
    $delete_cart = "DELETE FROM cart WHERE u_id = '$u_id'";
    if (!mysqli_query($conn, $delete_cart)) {
        throw new Exception("Failed to Clear Cart: " . mysqli_error($conn));
    }

    // Commit transaction
    mysqli_commit($conn);

    // Redirect to invoice page
    header("Location: invoice.php?order_id=$order_number");
    exit();

} catch (Exception $e) {
    // Rollback transaction if any query fails
    mysqli_rollback($conn);
    header("Location: cart.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>
