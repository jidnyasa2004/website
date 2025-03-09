<?php 
// Include database connection file
include 'connection.php';  

// Get med_id from URL and ensure it's a string
$med_id = isset($_GET['med_id']) ? $_GET['med_id'] : '';

// Initialize variables
$name = $description = '';

// Fetch current medicine details
if (!empty($med_id)) {
    $sql = "SELECT name, description FROM medicine WHERE med_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $med_id); // 's' because med_id is a string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $description = htmlspecialchars($row['description']);
    } else {
        die("<script>alert('Medicine not found.'); window.location.href='medicine.php';</script>");
    }
}

// Update medicine details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['med_id'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $med_id = $_POST['med_id']; // Get med_id from form, not URL

        // Ensure med_id is valid before updating
        $sql = "UPDATE medicine SET name = ?, description = ? WHERE med_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $description, $med_id); // Use 'sss' since med_id is a string

        if ($stmt->execute()) {
            echo "<script>alert('Medicine updated successfully!'); window.location.href='medicine.php';</script>";
        } else {
            echo "<script>alert('Error updating medicine. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
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
</head>
<body class="bg-blue-900 text-white">
    <div class="container mx-auto max-w-md p-6 bg-white shadow-md rounded-md mt-10 text-gray-800">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Update Medicine</h2>
        <form method="POST">
            <input type="hidden" name="med_id" value="<?= htmlspecialchars($med_id) ?>"> <!-- Hidden field to pass med_id -->
            <div class="mb-4">
                <label for="name" class="block font-semibold text-blue-800">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="description" class="block font-semibold text-blue-800">Description:</label>
                <textarea id="description" name="description" required class="w-full border rounded px-3 py-2"><?= htmlspecialchars($description) ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">Update</button>
                <a href="medicine.php" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
