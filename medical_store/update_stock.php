<?php
// Include database connection file
include 'connection.php';

// Get stock_id from URL
$stock_id = $_GET['stock_id'] ?? null;

// Initialize variables
$name = $description = $type = $quantity = '';

// Fetch current stock details
if ($stock_id) {
    $sql = "SELECT name, description, type, quantity FROM stock WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $type = $row['type'];
        $quantity = $row['quantity'];
    } else {
        echo "Stock item not found.";
        exit;
    }
}

// Update stock details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    
    $sql = "UPDATE stock SET name = ?, description = ?, type = ?, quantity = ? WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $name, $description, $type, $quantity, $stock_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Stock updated successfully!'); window.location.href='stock.php';</script>";
    } else {
        echo "Error updating stock: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        h2 {
            color: #2563EB;
        }
        label {
            font-weight: bold;
            color: #1E3A8A;
        }
        input {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
        }
        input:focus {
            border-color: #2563EB;
            outline: none;
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
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: #1E3A8A;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #2563EB;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-2xl font-bold mb-6 text-center">Update Stock</h2>
    
    <form method="POST">
        <div class="mb-4">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-4">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?= htmlspecialchars($description) ?>" required>
        </div>
        <div class="mb-4">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?= htmlspecialchars($type) ?>" required>
        </div>
        <div class="mb-4">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($quantity) ?>" required>
        </div>
        
        <div class="text-center">
            <button type="submit">Update</button>
            <a href="stock.php" class="back-btn">Back</a>
        </div>
    </form>
</div>

</body>
</html>
