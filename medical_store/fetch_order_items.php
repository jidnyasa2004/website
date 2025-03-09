<?php
include 'connection.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $sql = "SELECT oi.quantity, oi.price, m.name FROM order_items oi 
            JOIN medicine m ON oi.med_id = m.med_id 
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='w-full border-collapse border border-gray-300'>
                <thead>
                    <tr class='bg-gray-200'>
                        <th class='border p-2'>Medicine Name</th>
                        <th class='border p-2'>Quantity</th>
                        <th class='border p-2'>Price</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr class='bg-white hover:bg-gray-100'>
                    <td class='border p-2 text-center'>" . htmlspecialchars($row['name']) . "</td>
                    <td class='border p-2 text-center'>" . htmlspecialchars($row['quantity']) . "</td>
                    <td class='border p-2 text-center'>₹" . htmlspecialchars($row['price']) . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='text-center text-gray-500'>No items found for this order.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>