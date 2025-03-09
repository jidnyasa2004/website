<?php
// Include the connection file
include 'connection.php';

// Initialize filter variable
$filter = $_GET['filter'] ?? '';

// Prepare the SQL query based on the filter
$sql = "SELECT * FROM medicine";
if ($filter) {
    $sql .= " WHERE type = ?";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($filter) {
    $stmt->bind_param('s', $filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: black;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        h1 {
            color: #2563EB;
            text-align: center;
        }
        .filter-dropdown {
            background: #2563EB;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            display: inline-block;
        }
        .filter-dropdown select {
            appearance: none;
            background: transparent;
            border: none;
            color: white;
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
            cursor: pointer;
            outline: none;
        }
        .filter-dropdown::after {
            content: '\25BC'; /* Down arrow */
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: white;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            height: 350px; /* Fixed Height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-card h2 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2563EB;
        }
        .product-card p {
            color: #666;
        }
        .product-card .price {
            color: #1E3A8A;
            font-weight: bold;
        }
        .back-to-home {
            background: #1E3A8A;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            display: inline-block;
            text-align: center;
            margin-top: 1rem;
        }
        .back-to-home:hover {
            background: #2563EB;
        }
    </style>
</head>
<body>
    <div class="container mt-10">
        <h1 class="text-2xl font-bold mb-6">Medicine Categories</h1>
        <form method="GET" class="mb-6">
            <div class="filter-dropdown">
                <select name="filter" onchange="this.form.submit()">
                    <option value="">Select Category</option>
                    <option value="tablet" <?php if ($filter == 'tablet') echo 'selected'; ?>>Tablet</option>
                    <option value="syrup" <?php if ($filter == 'syrup') echo 'selected'; ?>>Syrup</option>
                    <option value="cream" <?php if ($filter == 'cream') echo 'selected'; ?>>Cream</option>
               
                </select>
            </div>
        </form>
        
        <div class="grid-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    if (!empty($row['image_path'])) {
                        echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    }
                    echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='price'>₹" . htmlspecialchars($row['price']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No medicines available</p>";
            }
            $stmt->close();
            ?>
        </div>
        <a href="homenew.php" class="back-to-home">Back to Home</a>
    </div>
</body>
</html>
