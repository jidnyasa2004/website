<?php
// Include database connection file
include 'connection.php';

// Get stock_id from URL
$stock_id = $_GET['stock_id'] ?? null;
$message = "";

if ($stock_id) {
    // Check if the stock item exists before deleting
    $sql = "DELETE FROM stock WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $stock_id);

    if ($stmt->execute()) {
        $message = "✅ Stock item deleted successfully.";
    } else {
        $message = "❌ Error deleting stock item: " . $conn->error;
    }
} else {
    $message = "⚠️ Invalid stock ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .custom-bg {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6, #93c5fd); /* Same as delete_medicine.php */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .custom-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            padding: 20px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .message {
            font-weight: bold;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .success { color: green; background: #d4edda; }
        .error { color: red; background: #f8d7da; }
        .warning { color: orange; background: #fff3cd; }
    </style>
</head>
<body class="custom-bg">
    <div class="custom-card">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Delete Stock</h2>
        <p class="message 
            <?php echo (strpos($message, '✅') !== false) ? 'success' : ((strpos($message, '❌') !== false) ? 'error' : 'warning'); ?>">
            <?php echo $message; ?>
        </p>
        <a href="stock.php" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            Back 
        </a>
    </div>
</body>
</html>
