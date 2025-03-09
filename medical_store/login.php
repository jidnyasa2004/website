<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $passwordd = $_POST['passwordd'];

  // Query the database to check credentials
  $sql = "SELECT * FROM credential WHERE email='$email' AND password='$passwordd'";
  $result = $conn->query($sql);

  if (!$result) {
    die("Error executing query: " . $conn->error);
  }

  if ($result->num_rows > 0) {
    // Login successful 
    $_SESSION['email'] = $email;

    // Fetch user type
    $row = $result->fetch_assoc();
    $user_type = $row['user_type'];
    $_SESSION['user_type'] = $user_type;
    $usrname = $row['username'];
    $_SESSION['usrname'] = $usrname;

    if ($user_type == 'Admin') {
      $success_message = "Admin login successful! Redirecting...";
      echo '<script type="text/javascript"> 
          setTimeout(function() { 
            window.location.href = "admin.php"; 
          }, 2000); 
        </script>';
    } else if ($user_type == 'Customer') {
      $success_message = "Customer login successful! Redirecting...";
      echo '<script type="text/javascript"> 
          setTimeout(function() { 
            window.location.href = "homenew.php"; 
          }, 2000); 
        </script>';
    }
  } else {
    // Login failed
    $error_message = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Medical Store</title>

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #1E3A8A, #2563EB);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
    }

    .login-container {
      background: white;
      color: black;
      padding: 2rem;
      border-radius: 10px;
      width: 350px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .floating-placeholder {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .floating-placeholder input {
      border: 2px solid #1E3A8A;
      padding: 12px;
      border-radius: 6px;
      width: 100%;
      font-size: 16px;
      outline: none;
    }

    .floating-placeholder label {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      background: white;
      padding: 0 5px;
      font-size: 14px;
      color: #1E3A8A;
      transition: 0.3s;
    }

    .floating-placeholder input:focus+label,
    .floating-placeholder input:not(:placeholder-shown)+label {
      top: -8px;
      font-size: 12px;
      color: #2563EB;
    }

    .password-container {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #2563EB;
    }

    .login-btn {
      width: 100%;
      background: linear-gradient(to right, #2563EB, #1E3A8A);
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-btn:hover {
      background: #1E3A8A;
    }

    .links {
      margin-top: 1rem;
      display: flex;
      justify-content: space-between;
    }

    .links a {
      color: #2563EB;
      text-decoration: none;
      font-weight: bold;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .message {
      margin-top: 1rem;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Sign In</h2>
    <form method="POST" action="">
      <div class="floating-placeholder">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
      </div>
      <div class="floating-placeholder password-container">
        <input type="password" name="passwordd" id="passwordd" placeholder=" " required>
        <label for="passwordd">Password</label>
        <i class="toggle-password fas fa-eye" onclick="togglePassword()"></i>
      </div>
      <button type="submit" class="login-btn">Login</button>
      <div class="links">
        <a href="forgot_password.php">Forgot Password?</a>
        <a href="register.php">Sign Up</a>
      </div>
    </form>
    <?php
    if (isset($success_message)) {
      echo '<p class="message text-green-500">' . $success_message . '</p>';
    }
    if (isset($error_message)) {
      echo '<p class="message text-red-500">' . $error_message . '</p>';
    }
    ?>
  </div>
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('passwordd');
      const passwordToggle = document.querySelector('.toggle-password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
      }
    }
  </script>
</body>

</html>