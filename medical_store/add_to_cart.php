<?php
include 'connection.php';
session_start();

// Set the correct timezone
date_default_timezone_set('Asia/Kolkata'); // Change to your timezone

// Ensure user is logged in
if (!isset($_SESSION['u_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$u_id = $_SESSION['u_id'];

// Decode incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$med_name = isset($data['name']) ? $conn->real_escape_string($data['name']) : '';
$price = isset($data['price']) ? $conn->real_escape_string($data['price']) : '';

// Validate input
if (!$med_name || !$price) {
    echo json_encode(['success' => false, 'message' => 'Invalid medicine details.']);
    exit;
}

// **Check if customer data exists AFTER adding details**
$check_sql = "SELECT * FROM customer WHERE u_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'no_customer']);
    exit;
}

// **Fetch the correct `med_id` from `medicine` table based on `name`**
$med_query = "SELECT med_id FROM medicine WHERE name = ?";
$med_stmt = $conn->prepare($med_query);
$med_stmt->bind_param("s", $med_name);
$med_stmt->execute();
$med_result = $med_stmt->get_result();

if ($med_result->num_rows > 0) {
    $med_row = $med_result->fetch_assoc();
    $med_id = $med_row['med_id'];
} else {
    echo json_encode(['success' => false, 'message' => 'Medicine not found.']);
    exit;
}

// **Check if the same medicine already exists in the cart for this user**
$check_cart_sql = "SELECT * FROM cart WHERE u_id = ? AND med_id = ?";
$check_cart_stmt = $conn->prepare($check_cart_sql);
$check_cart_stmt->bind_param("is", $u_id, $med_id);
$check_cart_stmt->execute();
$cart_result = $check_cart_stmt->get_result();

if ($cart_result->num_rows > 0) {
    // Medicine exists in cart, so increment the quantity
    $update_cart_sql = "UPDATE cart SET quantity = quantity + 1 WHERE u_id = ? AND med_id = ?";
    $update_cart_stmt = $conn->prepare($update_cart_sql);
    $update_cart_stmt->bind_param("is", $u_id, $med_id);
    
    if ($update_cart_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity.']);
    }
} else {
    // Medicine not in cart, so insert as new entry

    // **Generate new `cart_id` (CT00001, CT00002, etc.)**
    $last_cart_sql = "SELECT cart_id FROM cart ORDER BY cart_id DESC LIMIT 1";
    $last_cart_result = $conn->query($last_cart_sql);

    if ($last_cart_result && $last_cart_result->num_rows > 0) {
        $row = $last_cart_result->fetch_assoc();
        $last_cart_number = (int) substr($row['cart_id'], 2);
        $new_cart_id = "CT" . str_pad($last_cart_number + 1, 5, "0", STR_PAD_LEFT);
    } else {
        $new_cart_id = "CT00001";
    }

    // **Insert new item into `cart` table**
    $quantity = 1;
    $added_at = date('Y-m-d H:i:s'); // Now follows your system's timezone

    $insert_sql = "INSERT INTO cart (cart_id, u_id, med_id, name, quantity, added_at) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sisiss", $new_cart_id, $u_id, $med_id, $med_name, $quantity, $added_at);

    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add item to cart.']);
    }
}
?>
