<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Background Gradient */
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

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #2563EB;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
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

        <!-- Main content -->
        <div class="w-5/6 p-6">
            <div class="custom-card">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">Feedback List</h1>
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead class="table-header">
                        <tr>
                            <th class="py-3 px-5">Name</th>
                            <th class="py-3 px-5">Date</th>
                            <th class="py-3 px-5">Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "webdb";

                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch feedback data from the database
                        $sql = "SELECT name, date, feedback FROM feedback";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b hover:bg-gray-100'>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td class='py-2 px-5'>" . htmlspecialchars($row['feedback']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='py-2 px-4 border-b text-center'>No feedback available</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
