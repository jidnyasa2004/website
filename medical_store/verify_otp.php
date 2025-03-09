<?php
session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = implode("", $_POST['otp']);
    
    if ($entered_otp == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length === 1) {
                document.getElementById(nextFieldID).focus();
            }
        }
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-400 to-purple-500">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-center mb-4 text-gray-700">Verify OTP</h2>
        <?php if (isset($_SESSION['error'])) echo "<p class='text-red-500 text-center mb-2'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); ?>
        <form method="POST" class="space-y-4 flex flex-col items-center">
            <div class="flex space-x-2">
                <input type="text" name="otp[]" id="otp1" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="moveToNext(this, 'otp2')" required>
                <input type="text" name="otp[]" id="otp2" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="moveToNext(this, 'otp3')" required>
                <input type="text" name="otp[]" id="otp3" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="moveToNext(this, 'otp4')" required>
                <input type="text" name="otp[]" id="otp4" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="moveToNext(this, 'otp5')" required>
                <input type="text" name="otp[]" id="otp5" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="moveToNext(this, 'otp6')" required>
                <input type="text" name="otp[]" id="otp6" maxlength="1" class="w-12 h-12 text-center text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-300">Verify OTP</button>
        </form>
        <p class="text-center text-gray-500 text-sm mt-4">Didn’t receive the OTP? <a href="forgot_password.php" class="text-blue-500 hover:underline">Resend OTP</a></p>
    </div>
</body>
</html>
