<?php
// Include database connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['usrname'])) {
    header("Location: login.php");
    exit();
}

// Get current username from session
$usrname = $_SESSION['usrname'];

// Initialize variables
$new_email = $new_username = $new_password = "";
$success_message = $error_message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email'])) {
        $new_email = $_POST['email'];
        $sql = "UPDATE credential SET email='$new_email' WHERE username='$usrname'";
    } elseif (!empty($_POST['username'])) {
        $new_username = $_POST['username'];
        $sql = "UPDATE credential SET username='$new_username' WHERE username='$usrname'";
        $_SESSION['usrname'] = $new_username; // Update session username
    } elseif (!empty($_POST['password'])) {
        $new_password = $_POST['password'];
        $sql = "UPDATE credential SET password='$new_password' WHERE username='$usrname'";
    }

    if ($conn->query($sql) === TRUE) {
        $success_message = "Profile updated successfully! Redirecting to home...";
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "homenew.php";
                }, 2000);
              </script>';
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

// Fetch current user data
$sql = "SELECT email, username FROM credential WHERE username='$usrname'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1E3A8A, #2563EB);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background: white;
            color: black;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #FACC15;
            border: none;
            border-radius: 5px;
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #FFD700;
        }
        .message {
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            color: #4CAF50;
        }
        .error {
            color: #FF5733;
        }
        .back-link {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: #FACC15;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: #FFD700;
        }
    </style>
    <script>
        function showInputField() {
            let selectedOption = document.getElementById('updateOption').value;
            document.getElementById('emailField').style.display = 'none';
            document.getElementById('usernameField').style.display = 'none';
            document.getElementById('passwordField').style.display = 'none';

            if (selectedOption === 'email') {
                document.getElementById('emailField').style.display = 'block';
            } else if (selectedOption === 'username') {
                document.getElementById('usernameField').style.display = 'block';
            } else if (selectedOption === 'password') {
                document.getElementById('passwordField').style.display = 'block';
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Update Profile</h2>
        <form method="POST" action="">
            <label for="updateOption">Select an option to update:</label>
            <select id="updateOption" name="updateOption" onchange="showInputField()">
                <option value="" disabled selected>Select an option</option>
                <option value="email">Email</option>
                <option value="username">Username</option>
                <option value="password">Password</option>
            </select>

            <div id="emailField" style="display: none;">
                <label for="email">New Email</label>
                <input type="email" name="email" id="email">
            </div>
            <div id="usernameField" style="display: none;">
                <label for="username">New Username</label>
                <input type="text" name="username" id="username">
            </div>
            <div id="passwordField" style="display: none;">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password">
            </div>

            <button type="submit">Update</button>
        </form>

        <?php
        if (!empty($success_message)) {
            echo '<p class="message success">' . $success_message . '</p>';
        }
        if (!empty($error_message)) {
            echo '<p class="message error">' . $error_message . '</p>';
        }
        ?>

        <a class="back-link" href="homenew.php">Back to Home</a>
    </div>

</body>
</html>
