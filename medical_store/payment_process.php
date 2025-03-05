<?php
session_start(); // ✅ Place this at the very top

// Check if the session cart exists, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Include the database connection
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $order_date = date("Y-m-d");
    $total_amount = 0;
    $no_of_items = count($_SESSION['cart']);
    $med_id = ""; // Assuming med_id is not needed for now
    $date_time = date("Y-m-d H:i:s"); // Ensure current date-time

    if ($no_of_items > 0) {
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'];
        }
    }

    // Insert order details into the database
    $sql = "INSERT INTO orders (customer_name, order_date, total_amount, no_of_items, med_id, date_time)
            VALUES ('$customer_name', '$order_date', '$total_amount', '$no_of_items', '$med_id', '$date_time')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500 text-center font-semibold'>Order placed successfully!</p>";
    } else {
        echo "<p class='text-red-500 text-center font-semibold'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Order Summary</h2>

        <?php
        // Initialize variables
        $order_date = date("Y-m-d"); // Current date
        $total_amount = 0;
        $no_of_items = count($_SESSION['cart']);
        
        if ($no_of_items > 0) {
            $medicine_name = [];
            foreach ($_SESSION['cart'] as $item) {
                $medicine_name[] = htmlspecialchars($item['name']);
                $total_amount += $item['price'];
            }
            $medicine_name = implode(", ", $medicine_name); // Combine medicine names into a single string
        } else {
            $medicine_name = "No items";
        }
        ?>

        <!-- Order Form -->
        <form action="" method="post" class="space-y-4">
            <!-- Customer Name -->
            <div>
                <label for="customer_name" class="block text-lg font-semibold text-gray-700">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <!-- Bill Structure -->
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h3 class="text-lg font-semibold text-gray-800">Bill Summary</h3>
                <p class="text-gray-700"><strong>Customer Name:</strong> <span id="bill_customer_name" class="text-gray-900"></span></p>
                <p class="text-gray-700"><strong>Order Date:</strong> <?php echo $order_date; ?></p>
                <p class="text-gray-700"><strong>Selected Medicine:</strong> <?php echo $medicine_name; ?></p>
                <p class="text-gray-700"><strong>No. of Items:</strong> <?php echo $no_of_items; ?></p>
                <p class="text-gray-700"><strong>Total Amount:</strong> ₹<?php echo number_format($total_amount, 2); ?></p>
            </div>

            <!-- Button to Proceed to Payment -->
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Place Order
            </button>
            <a href="payment.php" class="block text-center bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition duration-300">
                Proceed to Payment
            </a>
        </form>
    </div>

</body>
<script>
    document.getElementById('customer_name').addEventListener('input', function() {
        document.getElementById('bill_customer_name').textContent = this.value;
    });
</script>
</html>
