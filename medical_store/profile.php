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
            background: linear-gradient(135deg, #2d8c8c, #73a1a1, #c3dfd9);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #2d8c8c, #73a1a1, #c3dfd9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #e8f6f6;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            color: #2d2d2d;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .text-gray-800 {
            color: #2d2d2d;
        }

        .shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .rounded {
            border-radius: 0.5rem;
        }

        .font-bold {
            font-weight: 700;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <header class="shadow p-4">
        <nav>
            <div class="logo text-xl font-bold">MyLogo</div>
            <div class="nav-links">
                <a href="#" class="text-gray-700 hover:text-gray-900">Home</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">About</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">Services</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">Contact</a>
            </div>
        </nav>
    </header>

    <main class="main-content container mx-auto my-10">
        <h1 class="text-3xl font-bold mb-5">User Profile</h1>
        <div class="bg-white p-5 shadow rounded">
            <p class="mb-2"><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p class="mb-2"><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <a href="update_profile.php" class="text-blue-500 hover:underline">Update Profile</a>
        </div>
    </main>

</body>

</html>
