<?php
session_start();
require 'connection.php'; // Database connection
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT u_id FROM credential WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999); // Generate 6-digit OTP
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        
        // Send OTP via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jidnyasapatil474@gmail.com';
            $mail->Password = 'elzngfmlzpubenwk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('jidnyasapatil474@gmail.com', 'Medical Store');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP for password reset is: $otp";
            
            if ($mail->send()) {
                header("Location: verify_otp.php");
                exit();
            } else {
                $_SESSION['error'] = "Email could not be sent. Try again later.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-400 to-purple-500">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-center mb-4 text-gray-700">Forgot Password</h2>
        <?php if (isset($_SESSION['message'])) echo "<p class='text-green-500 text-center mb-2'>" . $_SESSION['message'] . "</p>"; unset($_SESSION['message']); ?>
        <?php if (isset($_SESSION['error'])) echo "<p class='text-red-500 text-center mb-2'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); ?>
        <form method="POST" class="space-y-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12A4 4 0 118 12a4 4 0 018 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M12 14v4m-3 0h6"></path></svg>
                </span>
                <input type="email" name="email" required placeholder="Enter your email" class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-300">Submit</button>
        </form>
        <p class="text-center text-gray-500 text-sm mt-4">Remember your password? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
    </div>
</body>
</html>
