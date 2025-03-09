<?php
include 'connect.php';

function generateCartID($conn) {
    $query = "SELECT cart_id FROM cart ORDER BY cart_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $lastID = intval(substr($row['cart_id'], 5)); // Extract numeric part
        $newID = "CT" . str_pad($lastID + 1, 5, '0', STR_PAD_LEFT); // Increment and format
    } else {
        $newID = "CT00001"; // First cart entry
    }
    return $newID;
}
?>
