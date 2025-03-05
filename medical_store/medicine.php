<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Management</title>
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
            padding: 20px;
            min-height: auto; /* Ensures card resizes dynamically */
            display: flex;
            flex-direction: column;
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }

        .custom-card h2 {
            font-size: 1.4rem;
            font-weight: bold;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .custom-card p {
            flex-grow: 1; /* Ensures full visibility of description */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            max-width: 100%;
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

        .search-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: black;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563EB;
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

<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "webdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search variable
$search = $_GET['search'] ?? '';

// Fetch data for medicines
$sql = "SELECT * FROM medicines WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = '%' . $search . '%';
$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$medicines = $stmt->get_result();
?>

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
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Medicine</h1>
            <form method="GET" class="w-1/3">
                <input type="text" name="search" placeholder="Search..." class="search-input" value="<?= htmlspecialchars($search) ?>">
            </form>
        </div>

        <!-- Medicine List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $medicines->fetch_assoc()): ?>
                <div class="custom-card">
                    <h2><?= htmlspecialchars($row['name']) ?></h2>
                    <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <div class="mt-4 flex justify-between">
                        <a href="update_medicine.php?med_id=<?= htmlspecialchars($row['med_id']) ?>" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</a>
                        <a href="delete_medicine.php?med_id=<?= htmlspecialchars($row['med_id']) ?>" class="bg-red-500 text-white px-4 py-2 rounded">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
// Close connection
$stmt->close();
$conn->close();
?>

</body>
</html>
