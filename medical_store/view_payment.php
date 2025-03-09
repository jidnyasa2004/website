<?php
session_start();
include 'connection.php';

// Handle status update request via AJAX
if (isset($_POST['update_status'])) {
    $payment_id = $_POST['payment_id'];
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update payment status
        $update_payment = $conn->prepare("UPDATE payment SET payment_status = ?, payment_datetime = NOW() WHERE payment_id = ?");
        $update_payment->bind_param("ss", $new_status, $payment_id);
        $update_payment->execute();

        // If status is "Completed", update order status too
        if ($new_status === 'Completed') {
            $update_order = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
            $completed_status = "Completed";
            $update_order->bind_param("ss", $completed_status, $order_id);
            $update_order->execute();
        }

        // Commit transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "error";
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: black;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: black;
            /* Set text color to black */
            background-color: white;
            /* Ensure background remains white */
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

        .custom-sidebar li:hover,
        .custom-sidebar li a:hover {
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
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold">Payment List</h2>
                <a href="logout1.php" class="bg-red-600 text-white py-2 px-4 rounded">Logout</a>
            </div>

            <div class="overflow-x-auto mt-8">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-blue-500 text-black">
                        <tr>
                            <th class="py-3 px-5">Payment ID</th>
                            <th class="py-3 px-5">Order ID</th>
                            <th class="py-3 px-5">Amount</th>
                            <th class="py-3 px-5">Payment Mode</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5">Date & Time</th>
                            <th class="py-3 px-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT payment_id, order_id, amount, payment_mode, 
                                   payment_status, payment_datetime 
                            FROM payment ORDER BY payment_datetime";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b' id='row-{$row['payment_id']}'>";
                                echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['payment_id']) . "</td>";
                                echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['order_id']) . "</td>";
                                echo "<td class='py-2 px-5 text-center'>₹" . number_format($row['amount'], 2) . "</td>";
                                echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['payment_mode']) . "</td>";
                                echo "<td class='py-2 px-5 status text-center' data-id='{$row['payment_id']}'>" . htmlspecialchars($row['payment_status']) . "</td>";
                                echo "<td class='py-2 px-5 text-center'>" . htmlspecialchars($row['payment_datetime']) . "</td>";
                                echo "<td class='py-2 px-5 text-center'>
                                <button class='btn-update' data-id='{$row['payment_id']}' data-order='{$row['order_id']}'>
                                    <i class='fas fa-edit'></i> Update
                                </button>
                            </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='py-2 px-4 text-center'>No payments found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(".btn-update").click(function () {
            var paymentId = $(this).data("id");
            var orderId = $(this).data("order");

            console.log("Updating Payment ID:", paymentId); // Debugging step

            Swal.fire({
                title: "Update Payment Status",
                input: "select",
                inputOptions: {
                    "Pending": "Pending",
                    "Completed": "Completed"
                },
                inputPlaceholder: "Select status",
                showCancelButton: true,
                confirmButtonText: "Update",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    var newStatus = result.value;
                    $.ajax({
                        url: "view_payment.php",
                        type: "POST",
                        data: { update_status: true, payment_id: paymentId, order_id: orderId, new_status: newStatus },
                        success: function (response) {
                            if (response === "success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Payment status updated successfully.",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location.reload(); // Auto-refresh the page
                                });
                            } else {
                                Swal.fire("Error", "Failed to update status.", "error");
                            }
                        }
                    });
                }
            });
        });

    </script>

</body>

</html>