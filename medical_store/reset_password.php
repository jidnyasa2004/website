<?php
session_start();
include 'connection.php'; // Include your database connection file

if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        $email = $_SESSION['email'];
        
        $stmt = $conn->prepare("UPDATE credential SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $password, $email);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Password reset successfully! Redirecting to login...";
            unset($_SESSION['otp_verified']);
            unset($_SESSION['email']);
            header("refresh:3;url=login.php"); // Redirect to login page after 3 seconds
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-400 to-purple-500">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-center mb-4 text-gray-700">Reset Password</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='text-red-500 text-center mb-2'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='text-green-500 text-center mb-2'>" . $_SESSION['success'] . "</p>";
        }
        ?>
        <form method="POST" class="space-y-4">
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a5 5 0 00-5 5v3a2 2 0 00-1 1.732V14a2 2 0 002 2h8a2 2 0 002-2v-2.268a2 2 0 00-1-1.732V7a5 5 0 00-5-5z" clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="password" name="password" placeholder="New Password" class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a5 5 0 00-5 5v3a2 2 0 00-1 1.732V14a2 2 0 002 2h8a2 2 0 002-2v-2.268a2 2 0 00-1-1.732V7a5 5 0 00-5-5z" clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-300">Reset Password</button>
        </form>
    </div>
</body>
</html>
