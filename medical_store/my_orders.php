<?php
include 'connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['u_id'])) {
    echo "<script>alert('You need to log in to view your orders.'); window.location.href='login.php';</script>";
    exit;
}

$u_id = $_SESSION['u_id']; // Logged-in user's ID

// Fetch orders for the logged-in user (use c_id instead of u_id)
$sql = "SELECT * FROM `orders` WHERE c_id = ? ORDER BY date_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 p-6">

    <!-- Navbar -->
    <header class="bg-blue-800 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold"><i class="fa-solid fa-file-invoice-dollar"></i> My Orders</h1>
        <a href="homenew.php" class="text-lg bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500 transition">
            <i class="fa-solid fa-home"></i> Home
        </a>
    </header>

    <div class="mt-6">
        <?php if ($result->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Order ID</th>
                            <th class="border p-2">Date</th>
                            <th class="border p-2">Total Amount</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="bg-white hover:bg-gray-100">
                                <td class="border p-2 text-center"><?php echo htmlspecialchars($row['order_id']); ?></td>
                                <td class="border p-2 text-center"><?php echo htmlspecialchars($row['date_time']); ?></td>
                                <td class="border p-2 text-center">₹<?php echo htmlspecialchars($row['total_amount']); ?></td>
                                <td class="border p-2 text-center">
                                    <span class="px-2 py-1 rounded 
                                        <?php echo ($row['order_status'] == 'Completed') ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white'; ?>">
                                        <?php echo htmlspecialchars($row['order_status']); ?>
                                    </span>
                                </td>
                                <td class="border p-2 text-center">
                                    <button class="toggle-items bg-blue-500 text-white px-3 py-1 rounded"
                                        data-order-id="<?php echo $row['order_id']; ?>">View Items</button>
                                </td>
                            </tr>
                            <tr class="order-items hidden bg-gray-50" id="items-<?php echo $row['order_id']; ?>">
                                <td colspan="5" class="p-4">
                                    <div class="p-4 border rounded bg-white shadow-sm">
                                        <h3 class="text-lg font-semibold mb-2">Order Items</h3>
                                        <div class="items-content"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">No orders found.</p>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function () {
            $(".toggle-items").click(function () {
                var orderId = $(this).data("order-id");
                var itemsRow = $("#items-" + orderId);
                var itemsContent = itemsRow.find(".items-content");

                if (itemsRow.is(":visible")) {
                    itemsRow.hide();
                } else {
                    if (itemsContent.is(":empty")) {
                        $.ajax({
                            url: "fetch_order_items.php",
                            type: "GET",
                            data: { order_id: orderId },
                            success: function (response) {
                                itemsContent.html(response);
                            }
                        });
                    }
                    itemsRow.show();
                }
            });
        });
    </script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
