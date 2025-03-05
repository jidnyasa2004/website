<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-xl font-bold mb-4">Order Summary</h1>
        <?php
            include 'cart.php';
            
            // Assuming cart.php returns an array $cart_items with relevant details
            if (!empty($cart_items)) {
                foreach ($cart_items as $cart) {
                    echo "<div class='p-4 border rounded-lg bg-gray-50 mb-4'>";
                    echo "<p><strong>Medicine:</strong> " . $cart['medicine_name'] . "</p>";
                    echo "<p><strong>Quantity:</strong> " . $cart['no_of_items'] . "</p>";
                    echo "<p><strong>Order Date:</strong> " . $cart['dateandtime'] . "</p>";
                    echo "<p><strong>Total Amount:</strong> $" . number_format($cart['total_amount'], 2) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-red-500'>No items in the cart.</p>";
            }
        ?>
        <button class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Proceed to Payment</button>
    </div>
</body>
</html>
