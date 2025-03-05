<?php
// Include database connection
$conn = new mysqli("localhost", "root", "", "webdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$successMsg = $errorMsg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $exp_date = $_POST['exp_date'];
    $mfg_date = $_POST['mfg_date'];
    $price = $_POST['price'];

    // Image upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($imageFileType, $allowedTypes)) {
        $errorMsg = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;

        // Insert data using prepared statements
        $sql = "INSERT INTO medicines (name, description, type, quantity, exp_date, mfg_date, price, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissds", $name, $description, $type, $quantity, $exp_date, $mfg_date, $price, $image_path);

        if ($stmt->execute()) {
            $successMsg = "Medicine added successfully!";
        } else {
            $errorMsg = "Error: " . $stmt->error;
        }
    } else {
        $errorMsg = "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .custom-sidebar {
            background: #1E3A8A;
            min-height: 100vh;
            padding: 20px;
        }
        .custom-sidebar ul {
            list-style: none;
            padding: 0;
        }
        .custom-sidebar li {
            margin: 15px 0;
            padding: 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .custom-sidebar li:hover, .custom-sidebar li a:hover {
            background: #2563EB;
        }
        .custom-sidebar li a {
            color: white;
            text-decoration: none;
            display: block;
        }
        .form-container {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            max-width: 600px;
            margin: auto;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .input-field:focus {
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
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
        .alert-success {
            background: #4CAF50;
            color: white;
        }
        .alert-error {
            background: #F44336;
            color: white;
        }
    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <div class="w-1/6 custom-sidebar text-white">
        <h1 class="text-2xl font-bold mb-6">MEDICAL STORE</h1>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="medicine.php">Medicine</a></li>
            <li><a href="add_medicine.php">Add Medicine</a></li>
            <li><a href="stock.php">Stock</a></li>
            <li><a href="view_order.php">Order</a></li>
            <li><a href="view_payment.php">Payment</a></li>
            <li><a href="view_feedback.php">Feedback</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Add Medicine</h1>

        <div class="form-container">
            <!-- Success/Error Messages -->
            <?php if ($successMsg): ?>
                <div class="alert alert-success"><?= $successMsg ?></div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <div class="alert alert-error"><?= $errorMsg ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block font-bold mb-2">Medicine Name</label>
                    <input type="text" name="name" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Description</label>
                    <textarea name="description" class="input-field" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Type</label>
                    <input type="text" name="type" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Quantity</label>
                    <input type="number" name="quantity" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Expiration Date</label>
                    <input type="date" name="exp_date" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Manufacturing Date</label>
                    <input type="date" name="mfg_date" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Price</label>
                    <input type="number" name="price" step="0.01" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Medicine Image</label>
                    <input type="file" name="image" class="input-field" required>
                </div>
                <div class="text-center">
                    <button type="submit">Add Medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
