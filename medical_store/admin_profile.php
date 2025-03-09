<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

// Ensure user session exists
if (!isset($_SESSION['u_id'])) {
    echo "<script>
        Swal.fire({
            title: 'Session Expired',
            text: 'Please log in again.',
            icon: 'error'
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>";
    exit();
}

$u_id = $_SESSION['u_id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT * FROM admin WHERE u_id = ?");
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Generate new admin_id if missing
if (!$admin) {
    $stmt = $conn->prepare("SELECT MAX(admin_id) FROM admin");
    $stmt->execute();
    $stmt->bind_result($lastAdminId);
    $stmt->fetch();
    $stmt->close();

    $newAdminId = ($lastAdminId) ? 'A' . str_pad(intval(substr($lastAdminId, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'A001';
}

// Handle form submission (Save Admin Details)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$admin) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO admin (admin_id, u_id, name, email, contact) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $newAdminId, $u_id, $name, $email, $contact);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Profile updated successfully.',
                icon: 'success'
            }).then(() => {
                window.location.href = 'admin_profile.php';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
                icon: 'error'
            });
        </script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if (!$admin): ?>
    <script>
        setTimeout(function() {
            Swal.fire({
                title: 'Profile Incomplete',
                text: 'Please enter your details first.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Enter Details',
                allowOutsideClick: false
            });
        }, 500);
    </script>

    <h2>Enter Admin Details</h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Contact:</label>
        <input type="text" name="contact" required><br>
        
        <button type="submit">Save</button>
    </form>

<?php else: ?>
    <h2>Admin Profile</h2>
    <p><strong>Admin ID:</strong> <?= htmlspecialchars($admin['admin_id']) ?></p>
    <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($admin['contact']) ?></p>
    <a href="admin.php">Back to Dashboard</a>
<?php endif; ?>

</body>
</html>
