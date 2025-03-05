<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Store customer name in session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_name'])) {
    $_SESSION['customer_name'] = $_POST['customer_name'];
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Cash on Delivery Order Placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_order'])) {
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

        // ✅ Redirect to Invoice Page
        echo "<script>
            alert('Order Placed Successfully! Redirecting to Invoice...');
            window.location.href = 'invoice.php?order_id=$order_id';
        </script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='cart.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
<main class="container mx-auto p-6 max-w-lg bg-gray-800 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold mb-5 text-center text-yellow-400">🛒 Your Cart</h1>

    <!-- Name Form -->
    <form method="POST" class="mb-5">
        <input type="text" name="customer_name" class="w-full p-2 rounded text-black border-none focus:ring-2 focus:ring-yellow-400" placeholder="Enter your name" value="<?= isset($_SESSION['customer_name']) ? htmlspecialchars($_SESSION['customer_name']) : '' ?>" required>
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white w-full p-2 mt-2 rounded">Save Name</button>
    </form>

    <?php
    $totalAmount = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            $quantity = isset($item['quantity']) ? $item['quantity'] : 1; // Ensure quantity exists
            $itemTotal = $item['price'] * $quantity;
            
            $totalAmount += $itemTotal;
            echo '<div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg mt-3 shadow">';
            echo '<div>';
            echo '<h2 class="text-lg font-semibold">' . htmlspecialchars($item['name']) . '</h2>';
            echo '<p class="text-green-400 font-bold">₹' . htmlspecialchars($itemTotal) . ' (' . $quantity . 'x)</p>';

            echo '</div>';
            echo '<button class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded remove-item" data-index="' . $index . '">🗑️ Remove</button>';
            echo '</div>';
        }
        echo '<div class="mt-5 text-right text-lg font-bold text-yellow-300">Total: ₹' . $totalAmount . '</div>';
        echo '<button id="orderButton" class="bg-blue-500 hover:bg-blue-600 text-white w-full p-3 mt-4 rounded text-lg font-semibold">🛍️ Proceed to Order</button>';
    } else {
        echo '<p class="text-center text-gray-400 mt-5">Your cart is empty. Start adding products! 🛍️</p>';
    }
    ?>
</main>

<script>
document.getElementById('orderButton')?.addEventListener('click', function (event) {
    event.preventDefault();
    let customerName = document.querySelector('input[name="customer_name"]').value.trim();
    if (!customerName) {
        Swal.fire('Oops!', 'Please enter your name before proceeding.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Choose Payment Method',
        text: 'Select your preferred payment option:',
        icon: 'info',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '💳 Pay Online',
        denyButtonText: '💵 Cash on Delivery',
        cancelButtonText: '❌ Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Service Unavailable', 'Online payment is currently not available.', 'error');
        } else if (result.isDenied) {
            document.getElementById('codForm').submit();
        }
    });
});
</script>

<form method="POST" id="codForm" action="cart.php">
    <input type="hidden" name="cod_order" value="1">
</form>
</body>
</html> 
