<?php
// Connect to the database
include 'connection.php';

// Define the low stock threshold
$low_stock_threshold = 5;

// Fetch low stock items from the stock table
$low_stock_query = $conn->prepare("
    SELECT medicine.name, stock.quantity 
    FROM stock 
    JOIN medicine ON stock.med_id = medicine.med_id 
    WHERE stock.quantity <= ?
");
$low_stock_query->bind_param("i", $low_stock_threshold);
$low_stock_query->execute();
$low_stock_result = $low_stock_query->get_result();

// Store low stock items
$low_stock_items = [];
while ($row = $low_stock_result->fetch_assoc()) {
    $low_stock_items[] = $row;
}
$low_stock_query->close();

// Fetch data for overview cards
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM medicine");
$stmt->execute();
$stmt->bind_result($totalMedicines);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT SUM(quantity) as stock FROM stock");
$stmt->execute();
$stmt->bind_result($totalStock);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as totalOrders FROM orders");
$stmt->execute();
$stmt->bind_result($totalOrders);
$stmt->fetch();
$stmt->close();

// Fetch data for tables
$medicines = $conn->query("SELECT * FROM medicine LIMIT 5");

// Adjust this query based on your actual column names in the 'orders' table
$orders = $conn->query("SELECT order_id, total_amount, no_of_items FROM orders LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
        }

        .custom-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .custom-sidebar {
            background: #1E3A8A;
            min-height: 100vh;
        }

        .custom-sidebar li {
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .custom-sidebar li:hover {
            background: #2563EB;
        }

        button {
            background: #2563EB;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #1E3A8A;
        }
    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <div class="w-1/6 custom-sidebar text-white h-screen p-4">
        <h1 class="text-2xl font-bold mb-6">MEDICAL STORE</h1>
        <ul>
            <li class="mt-4"><a href="admin.php">Dashboard</a></li>
            <li class="mt-4"><a href="medicine.php">Medicine</a></li>
            <li class="mt-4"><a href="add_medicine.php">Add Medicine</a></li>
            <li class="mt-4"><a href="stock.php">Stock</a></li>
            <li class="mt-4"><a href="view_order.php">Order</a></li>
            <li class="mt-4"><a href="view_payment.php">Payment</a></li>
            <li class="mt-4"><a href="view_feedback.php">Feedback</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold">Dashboard</h2>
            <a href="logout1.php" class="bg-red-600 text-white py-2 px-4 rounded">Logout</a>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-3 gap-6 mt-8">
            <div class="custom-card p-4 text-center">
                <h3 class="text-xl font-bold text-gray-700">Total Medicines</h3>
                <p class="text-2xl"><?= htmlspecialchars($totalMedicines) ?></p>
            </div>
            <div class="custom-card p-4 text-center">
                <h3 class="text-xl font-bold text-gray-700">Stock</h3>
                <p class="text-2xl"><?= htmlspecialchars($totalStock) ?></p>
            </div>
            <div class="custom-card p-4 text-center">
                <h3 class="text-xl font-bold text-gray-700">Total Orders</h3>
                <p class="text-2xl"><?= htmlspecialchars($totalOrders) ?></p>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <?php if (count($low_stock_items) > 0): ?>
        <div class="bg-yellow-400 text-black p-4 mt-8 rounded">
            <h3 class="text-xl font-bold mb-4">⚠️ Low Stock Alert</h3>
            <ul>
                <?php foreach ($low_stock_items as $item): ?>
                    <li>🚨 <?= htmlspecialchars($item['name']) ?>: Only <?= htmlspecialchars($item['quantity']) ?> left!</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Medicines Table -->
        <div class="mt-8 custom-card p-4">
            <h3 class="text-xl font-bold mb-4 text-gray-700">Medicines</h3>
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4" align="left">Medicine</th>
                 
                        <th class="py-2 px-4">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $medicines->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4"><?= htmlspecialchars($row['name']) ?></td>
                   
                        <td class="py-2 px-4">₹<?= htmlspecialchars($row['price']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Orders Table -->
        <div class="mt-8 custom-card p-4">
            <h3 class="text-xl font-bold mb-4 text-gray-700">Recent Orders</h3>
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4" align="left">Order ID</th>
                        <th class="py-2 px-4" align="left">Total Amount</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4"><?= htmlspecialchars($row['order_id']) ?></td>
                        <td class="py-2 px-4">₹<?= htmlspecialchars($row['total_amount']) ?></td>
                      
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
