<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch customer ID based on logged-in user
$email = $_SESSION['email'];
$query_customer = "SELECT c_id FROM customer WHERE email = '$email'";
$result_customer = $conn->query($query_customer);

if ($result_customer->num_rows > 0) {
    $customer = $result_customer->fetch_assoc();
    $c_id = $customer['c_id'];

    // Fetch invoices (orders linked with payments)
    $query_invoices = "
        SELECT o.order_id, o.total_amount, o.no_of_items, o.date_time, o.order_status,
               p.payment_mode, p.payment_status
        FROM orders o
        LEFT JOIN payment p ON o.order_id = p.order_id
        WHERE o.c_id = '$c_id'
        ORDER BY o.date_time DESC";
    
    $result_invoices = $conn->query($query_invoices);
} else {
    $c_id = null;
    $result_invoices = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invoices - Patil Medical Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <header class="bg-blue-800 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold"><i class="fa-solid fa-file-invoice-dollar"></i> My Invoices</h1>
        <a href="homenew.php" class="text-lg bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500 transition">
            <i class="fa-solid fa-home"></i> Home
        </a>
    </header>

    <!-- Invoice List -->
    <div class="container mx-auto my-6 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Your Invoices</h2>

        <?php if ($c_id && $result_invoices && $result_invoices->num_rows > 0): ?>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Total Amount</th>
                        <th class="border px-4 py-2">Items</th>
                        <th class="border px-4 py-2">Payment Mode</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($invoice = $result_invoices->fetch_assoc()): ?>
                        <tr class="text-center hover:bg-gray-100">
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($invoice['order_id']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($invoice['date_time']); ?></td>
                            <td class="border px-4 py-2 font-bold text-green-600">₹<?php echo htmlspecialchars($invoice['total_amount']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($invoice['no_of_items']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($invoice['payment_mode']); ?></td>
                            <td class="border px-4 py-2 font-semibold 
                                <?php echo $invoice['payment_status'] === 'Completed' ? 'text-green-600' : 'text-red-500'; ?>">
                                <?php echo htmlspecialchars($invoice['payment_status']); ?>
                            </td>
                            <td class="border px-4 py-2">
                                <a href="invoice.php?order_id=<?php echo $invoice['order_id']; ?>"
                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                   View Invoice
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-red-500 text-center">No invoices found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
