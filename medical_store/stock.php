<?php
// Include database connection file
include 'connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $med_id = $_POST['med_id'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];

    // Insert into stock table
    $sql = "INSERT INTO stock (med_id, type, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $med_id, $type, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Stock added successfully!'); window.location.href='stock.php';</script>";
    } else {
        echo "Error adding stock: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Gradient Background */
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
        }

        /* Sidebar */
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

        /* Card Style */
        .custom-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            max-width: 900px;
            margin: auto;
        }

        /* Table Header */
        .table-header {
            background-color: #2563EB;
            color: white;
        }

        /* Buttons */
        .btn-primary {
            background: #2563EB;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #1E3A8A;
        }

        .input-field {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
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
        <h1 class="text-3xl font-bold text-center mb-6">Stock Management</h1>

        <!-- Add Stock Form -->
        <div class="custom-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Add Stock</h3>
            <form method="POST" class="flex flex-col space-y-4">
                <div>
                    <label class="block text-gray-700">Medicine ID:</label>
                    <input type="number" name="med_id" required class="input-field">
                </div>
                <div>
                    <label class="block text-gray-700">Type:</label>
                    <input type="text" name="type" required class="input-field">
                </div>
                <div>
                    <label class="block text-gray-700">Quantity:</label>
                    <input type="number" name="quantity" required class="input-field">
                </div>
                <button type="submit" class="btn-primary">Add Stock</button>
            </form>
        </div>

        <!-- Stock Table -->
        <div class="mt-8 custom-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Stock Levels</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead class="table-header">
                        <tr>
                            <th class="py-3 px-5">Stock ID</th>
                            <th class="py-3 px-5">Name</th>
                            <th class="py-3 px-5">Description</th>
                            <th class="py-3 px-5">Type</th>
                            <th class="py-3 px-5">Quantity</th>
                            <th class="py-3 px-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT stock.stock_id, medicines.name, medicines.description, stock.type, stock.quantity
                                FROM stock
                                JOIN medicines ON stock.med_id = medicines.med_id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b hover:bg-gray-100'>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row["stock_id"]) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row["name"]) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row["description"]) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row["type"]) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row["quantity"]) . "</td>";
                                echo "<td class='py-2 px-5'>";
                                echo "<a href='update_stock.php?stock_id=" . htmlspecialchars($row["stock_id"]) . "' class='bg-yellow-500 text-white py-1 px-3 rounded'>Update</a> ";
                                echo "<a href='delete_stock.php?stock_id=" . htmlspecialchars($row["stock_id"]) . "' class='bg-red-500 text-white py-1 px-3 rounded'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-2 px-4 text-center'>No stock found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
