<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['u_id']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['u_id']; // Fetch logged-in user's u_id
$email = $_SESSION['email']; // Fetch logged-in user's email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    // Generate c_id (Customer ID) - Auto-incremented in the database
    $query = "SELECT MAX(CAST(SUBSTRING(c_id, 2) AS UNSIGNED)) AS last_id FROM customer";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $last_id = $row['last_id'] ?? 0;
    $new_cid = "C" . str_pad($last_id + 1, 5, "0", STR_PAD_LEFT);

    // Insert data into the customer table
    $sql = "INSERT INTO customer (c_id, u_id, name, email, contact, address) 
            VALUES ('$new_cid', '$u_id', '$name', '$email', '$contact', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Details added successfully!');
                window.location.href='homenew.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Enter Your Details</h2>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-semibold">Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly
                    class="w-full px-3 py-2 border rounded-lg bg-gray-200 cursor-not-allowed">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Contact</label>
                <input type="text" name="contact" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Address</label>
                <textarea name="address" required class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">Save Details</button>
        </form>
    </div>

</body>

</html>