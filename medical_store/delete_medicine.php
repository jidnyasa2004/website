<?php
// Include database connection file
include 'connection.php';

// Get med_id from URL
$med_id = $_GET['med_id'] ?? null;
$message = "";

if ($med_id) {
    // Check if the medicine is referenced in another table (e.g., stock)
    $check_sql = "SELECT COUNT(*) FROM stock WHERE med_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param('i', $med_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        $message = "❌ Cannot delete this medicine as it is referenced in the stock.";
    } else {
        // Proceed with deletion
        $sql = "DELETE FROM medicines WHERE med_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $med_id);

        if ($stmt->execute()) {
            $message = "✅ Medicine deleted successfully.";
        } else {
            $message = "❌ Error deleting medicine: " . $conn->error;
        }
    }
} else {
    $message = "⚠️ Invalid medicine ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .custom-bg {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6, #93c5fd); /* Matching admin.php */
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
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Delete Medicine</h2>
        <p class="message 
            <?php echo (strpos($message, '✅') !== false) ? 'success' : ((strpos($message, '❌') !== false) ? 'error' : 'warning'); ?>">
            <?php echo $message; ?>
        </p>
        <a href="medicine.php" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
           Back
        </a>
    </div>
</body>
</html>
