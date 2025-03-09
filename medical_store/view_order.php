<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
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
            <h2 class="text-3xl font-bold">Order List</h2>
            <a href="logout1.php" class="bg-red-600 text-white py-2 px-4 rounded">Logout</a>
        </div>

        <div class="overflow-x-auto mt-8">
            <table class="min-w-full bg-teal shadow-md rounded-lg">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-5">Order ID</th>
                        <th class="py-3 px-5">Customer Name</th>
                        <th class="py-3 px-5">No. of Items</th>
                        <th class="py-3 px-5">Total Amount</th>
                        <th class="py-3 px-5">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    <?php
                    // Fetch orders with customer name using JOIN
                    $sql = "SELECT o.order_id, c.name, o.no_of_items, o.total_amount, o.date_time 
                            FROM orders o 
                            JOIN customer c ON o.c_id = c.c_id 
                            ORDER BY o.date_time";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-100'>";
                            echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['order_id']) . "</td>";
                            echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['no_of_items']) . "</td>";
                            echo "<td class='py-2 px-5 text-center'>₹" . number_format($row['total_amount'], 2) . "</td>";
                            echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['date_time']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-2 px-4 text-center'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
