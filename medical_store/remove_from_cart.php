<?php
session_start();

// Read JSON input safely
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Check received data
if (!isset($data['index']) || !is_numeric($data['index'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request - index not received or not a number', 'received_data' => $data]);
    exit;
}

$index = (int) $data['index'];

// Debugging: Check if cart is set
if (!isset($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Cart session is not set']);
    exit;
}

// Debugging: Check if index exists in cart
if (!array_key_exists($index, $_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart', 'cart' => $_SESSION['cart']]);
    exit;
}

// Remove item from cart
unset($_SESSION['cart'][$index]);

// Re-index cart array
$_SESSION['cart'] = array_values($_SESSION['cart']);

echo json_encode(['success' => true, 'message' => 'Item removed successfully']);
?>
