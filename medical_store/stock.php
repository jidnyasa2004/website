<?php
// Include database connection
include 'connection.php';
session_start(); // Start session for message storage

// Function to generate new stk_id with format S00001, S00002...
function generateStockID($conn)
{
    $sql = "SELECT stk_id FROM stock ORDER BY stk_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastID = $row['stk_id'];
        $num = intval(substr($lastID, 1)) + 1; // Extract numeric part and increment
        return 'S' . str_pad($num, 5, '0', STR_PAD_LEFT);
    } else {
        return 'S00001'; // First ID if no records exist
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stk_id = generateStockID($conn);
    $med_id = $_POST['med_id'];
    $quantity = $_POST['quantity'];

    // Insert into stock table
    $sql = "INSERT INTO stock (stk_id, med_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $stk_id, $med_id, $quantity);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Stock Added Successfully!',
                    showConfirmButton: false,
                    timer: 2000
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error adding stock: " . $conn->error . "',
                    showConfirmButton: true
                });
              </script>";
    }
}

// Fetch medicine names from the medicines table
$medicines = [];
$medQuery = "SELECT med_id, name FROM medicine";
$medResult = $conn->query($medQuery);
if ($medResult->num_rows > 0) {
    while ($row = $medResult->fetch_assoc()) {
        $medicines[] = $row;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Add Stock</h3>
                <form method="POST" action="">
                    <!-- Medicine Dropdown -->
                    <label class="block mb-2 font-medium text-black">Select Medicine:</label>
                    <select name="med_id" required class="w-full p-2 border rounded-md mb-4 text-black">
                        <option value="" class="text-black">-- Select Medicine --</option>
                        <?php foreach ($medicines as $medicine): ?>
                            <option class="text-black" value="<?= $medicine['med_id']; ?>">
                                <?= htmlspecialchars($medicine['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Quantity Input -->
                    <label class="block mb-2 font-medium text-black">Quantity:</label>
                    <input type="number" name="quantity" min="1" required
                        class="w-full p-2 border rounded-md mb-4 text-black">

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 ">Add
                        Stock</button>
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
                                <th class="py-3 px-5">Quantity</th>
                                <th class="py-3 px-5">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT stock.stk_id, medicine.name, medicine.description, stock.quantity
                                FROM stock
                                JOIN medicine ON stock.med_id = medicine.med_id";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b hover:bg-gray-100'>";
                                    echo "<td class='py-2 px-5'>" . htmlspecialchars($row["stk_id"]) . "</td>";
                                    echo "<td class='py-2 px-5'>" . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td class='py-2 px-5'>" . htmlspecialchars($row["description"]) . "</td>";
                                    echo "<td class='py-2 px-5'>" . htmlspecialchars($row["quantity"]) . "</td>";
                                    echo "<td class='py-2 px-5'>";
                                    echo "<a href='update_stock.php?stk_id=" . htmlspecialchars($row["stk_id"]) . "' class='bg-yellow-500 text-white py-1 px-3 rounded'>Update</a> ";
                                    echo "<a href='delete_stock.php?stk_id=" . htmlspecialchars($row["stk_id"]) . "' class='bg-red-500 text-white py-1 px-3 rounded'>Delete</a>";
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