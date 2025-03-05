<?php
session_start(); // ✅ Place this at the very top

// Include the database connection
include 'connection.php';

// Fetch the latest order details
$order_id = "";
$customer_name = "";
$order_date = "";
$total_amount = 0;
$no_of_items = 0;

$sql = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $order_id = $row['order_id'];
    $customer_name = $row['customer_name'];
    $order_date = $row['order_date'];
    $total_amount = $row['total_amount'];
    $no_of_items = $row['no_of_items'];
} else {
    echo "No recent order found.";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Payment Details</h2>
        
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-800">Order Summary</h3>
            <p class="text-gray-700"><strong>Order ID:</strong> <?php echo $order_id; ?></p>
            <p class="text-gray-700"><strong>Customer Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
            <p class="text-gray-700"><strong>Order Date:</strong> <?php echo $order_date; ?></p>
            <p class="text-gray-700"><strong>No. of Items:</strong> <?php echo $no_of_items; ?></p>
            <p class="text-gray-700"><strong>Total Amount:</strong> ₹<?php echo number_format($total_amount, 2); ?></p>
        </div>

        <form action="payment_process.php" method="post" class="space-y-4 mt-4">
            <!-- Payment Details -->
            <div>
                <label for="card_number" class="block text-lg font-semibold text-gray-700">Card Number</label>
                <input type="text" id="card_number" name="card_number" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label for="expiry_date" class="block text-lg font-semibold text-gray-700">Expiry Date</label>
                <input type="text" id="expiry_date" name="expiry_date" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="MM/YY" required>
            </div>

            <div>
                <label for="cvv" class="block text-lg font-semibold text-gray-700">CVV</label>
                <input type="text" id="cvv" name="cvv" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <!-- Button to Confirm Payment -->
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Confirm Payment
            </button>
        </form>
    </div>

</body>
</html>
