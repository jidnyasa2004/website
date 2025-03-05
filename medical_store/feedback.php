<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

if (!isset($_SESSION['usrname'])) {
    header("Location: homenew.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A); /* Matching gradient */
            font-family: 'Arial', sans-serif;
            color: white;
        }

        header {
            background: #0077b6;
            padding: 1rem;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
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

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        label {
            font-weight: bold;
            color: #0077b6;
        }

        input, textarea {
            border: 2px solid #0077b6;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: #2563EB;
            box-shadow: 0px 0px 5px rgba(37, 99, 235, 0.5);
        }

        button {
            background: #2563EB;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #1E3A8A;
        }

        /* Footer */
        footer {
            background: #0077b6;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .footer-links a {
            color: #ffdd57;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<header>
    <nav>
        <div class="logo">MEDICAL STORE</div>
        <div class="nav-links">
            <a href="homenew.php">Home</a>
            <a href="category.php">Category</a>
            <a href="cart.php">Cart</a>
            <a href="contact_us.php">Contact Us</a>
            <a href="feedback.php">Feedback</a>
        </div>
    </nav>
</header>

<main>
    <div class="container">
        <h1 class="text-3xl font-bold mb-5 text-center text-teal-700">Feedback</h1>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_SESSION['usrname'];
            $date = date("Y-m-d");
            $message = htmlspecialchars($_POST["message"]);

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "webdb";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT COUNT(*) AS count FROM feedback");
            $row = $result->fetch_assoc();
            $new_id = "FB" . str_pad($row["count"] + 1, 4, "0", STR_PAD_LEFT);

            $stmt = $conn->prepare("INSERT INTO feedback (fb_id, name, date, feedback) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $new_id, $name, $date, $message);

            if ($stmt->execute()) {
                echo "<p class='text-green-500 text-center'>Thank you for your feedback!</p>";
            } else {
                echo "<p class='text-red-500 text-center'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $_SESSION['usrname']; ?>" readonly>
            </div>
            <div class="mb-4">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="mb-4">
                <label for="message">Feedback:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <div>
        <p>&copy; <?php echo date("Y"); ?> Medical Store. All rights reserved.</p>
        <p>Contact us at <a href="mailto:support@medicalstore.com" style="color: #ffdd57;">support@medicalstore.com</a></p>
        <p>We provide the best quality medicines at affordable prices.</p>
        <div class="footer-links">
            <a href="about.php">About</a>
            <a href="contact_us.php">Contact Us</a>
            <?php if (!isset($_SESSION['usrname'])): ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</footer>

</body>
</html>
