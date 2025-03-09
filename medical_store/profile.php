<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['usrname'])) {
    header("Location: homenew.php");
    exit();
}

// Fetch user profile information
$usrname = $_SESSION['usrname'];
$sql = "SELECT email, username, u_id, password FROM credential WHERE username='$usrname'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
        }

        header {
            background: #0077b6;
            width: 100%;
            padding: 1rem 0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 1rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #ffdd57;
        }

        .profile-card {
            background: white;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
            text-align: center;
        }

        .profile-card h1 {
            color: #0077b6;
        }

        .profile-card p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .update-btn {
            background: #2563EB;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .update-btn:hover {
            background: #1E3A8A;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<header class="bg-blue-800 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold"><i class="fa-solid fa-file-invoice-dollar"></i> Profile</h1>
        <a href="homenew.php" class="text-lg bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500 transition">
            <i class="fa-solid fa-home"></i> Home
        </a>
    </header>

<main>
    <div class="profile-card">
        <h1 class="text-2xl font-bold">User Profile</h1>
        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <a href="update_profile.php" class="update-btn">Update Profile</a>
    </div>
</main>

</body>
</html>
