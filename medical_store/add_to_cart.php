<?php
// Include database connection file
include 'connection.php';

// Start session
session_start();

// Retrieve item data from POST request
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Check the received data
if (is_null($data)) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit();
}

$name = $data['name'] ?? null;
$price = $data['price'] ?? null;

// Debugging: Check if name and price are set
if (is_null($name) || is_null($price)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit();
}

// Obtain med_id corresponding to the medicine name from the database
$sql_med = "SELECT med_id FROM medicines WHERE name = ?";
$stmt_med = $conn->prepare($sql_med);
$stmt_med->bind_param('s', $name);
$stmt_med->execute();
$result_med = $stmt_med->get_result();

if ($result_med->num_rows > 0) {
    $row_med = $result_med->fetch_assoc();
    $med_id = $row_med['med_id'];

    // Add item to session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'med_id' => $med_id,
        'name' => $name,
        'price' => $price
    ];

    echo json_encode(['success' => true, 'message' => 'Medicine added to cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Medicine not found']);
}
?>
