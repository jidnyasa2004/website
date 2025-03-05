<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
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
            <h2 class="text-3xl font-bold">Payment List</h2>
            <a href="logout1.php" class="bg-red-600 text-white py-2 px-4 rounded">Logout</a>
        </div>

        <div class="overflow-x-auto mt-8">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-5">Invoice ID</th>
                        <th class="py-3 px-5">Order ID</th>
                        <th class="py-3 px-5">User ID</th>
                        <th class="py-3 px-5">Amount</th>
                        <th class="py-3 px-5">Date</th>
                        <th class="py-3 px-5">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM pays ORDER BY date DESC, time DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-100'>";
                            echo "<td class='py-2 px-5'>" . htmlspecialchars($row['invoice_id']) . "</td>";
                            echo "<td class='py-2 px-5'>" . htmlspecialchars($row['order_id']) . "</td>";
                            echo "<td class='py-2 px-5'>" . htmlspecialchars($row['u_id']) . "</td>";
                            echo "<td class='py-2 px-5'>₹" . number_format($row['amount'], 2) . "</td>";
                            echo "<td class='py-2 px-5'>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td class='py-2 px-5'>" . htmlspecialchars($row['time']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='py-2 px-4 text-center'>No payments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
