<?php
// Include database connection file
include 'connection.php';

// Get med_id from URL
$med_id = $_GET['med_id'] ?? null;

// Initialize variables
$name = $description = '';

// Fetch current medicine details
if ($med_id) {
    $sql = "SELECT name, description FROM medicines WHERE med_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $med_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
    } else {
        echo "Medicine not found.";
        exit;
    }
}

// Update medicine details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "UPDATE medicines SET name = ?, description = ? WHERE med_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $name, $description, $med_id);

    if ($stmt->execute()) {
        echo "<script>alert('Medicine updated successfully!'); window.location.href='medicine.php';</script>";
    } else {
        echo "Error updating medicine: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Medicine</title>
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
        input, textarea {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
        }
        input:focus, textarea:focus {
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
    <h2 class="text-2xl font-bold mb-6 text-center">Update Medicine</h2>
    <form method="POST">
        <div class="mb-4">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-4">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="text-center">
            <button type="submit">Update</button>
            <a href="medicine.php" class="back-btn">Back</a>
        </div>
    </form>
</div>

</body>
</html>
