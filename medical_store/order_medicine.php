<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available medicines
$medicines = [];
$result = $conn->query("SELECT med_id, name, price FROM medicines");

if ($result->num_rows == 0) {
    die("Error: No medicines found in the database.");
}

$total_amount = 0;
while ($row = $result->fetch_assoc()) {
    $quantity = rand(1, 5); // Assign a random quantity (or use user input)
    $subtotal = $quantity * $row['price'];
    $total_amount += $subtotal;

    $medicines[] = [
        'med_id' => $row['med_id'],
        'quantity' => $quantity,
        'price' => $row['price'],
        'subtotal' => $subtotal
    ];
}

// Insert new order into `orders` table
$order_sql = "INSERT INTO orders (no_of_items, date_time, total_amount, med_id) VALUES (?, NOW(), ?, ?)";
$stmt = $conn->prepare($order_sql);
$no_of_items = count($medicines);
$med_id = $medicines[0]['med_id']; // Using first med_id for reference, modify if needed
$stmt->bind_param("idi", $no_of_items, $total_amount, $med_id);
$stmt->execute();
$order_id = $conn->insert_id;

// Insert medicines into `order_medicine` table
$order_medicine_sql = "INSERT INTO order_medicine (order_id, med_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($order_medicine_sql);

foreach ($medicines as $med) {
    $stmt->bind_param("iiidd", $order_id, $med['med_id'], $med['quantity'], $med['price'], $med['subtotal']);
    $stmt->execute();
}

echo "✅ Order placed successfully. Order ID: " . $order_id;

// Close connections
$stmt->close();
$conn->close();
?>
