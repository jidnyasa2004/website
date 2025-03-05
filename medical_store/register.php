<?php
// Include database connection file
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $usrname = $_POST['usrname'];
    $passwordd = $_POST['passwordd'];
    $user_type = "user"; // Default user type is "user"

    // Server-side validation: Ensure password is at least 8 characters long
    if (strlen($passwordd) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Insert the user data into the database
        $sql = "INSERT INTO credential (email, username, password, user_type) VALUES ('$email', '$usrname', '$passwordd', '$user_type')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Registration successful! Redirecting to login page...";
            echo '<script type="text/javascript">
                    setTimeout(function() {
                      window.location.href = "index.php";
                    }, 2000);
                  </script>';
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Medical Store</title>
  
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  
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
    
    .register-container {
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

    .floating-placeholder input:focus + label,
    .floating-placeholder input:not(:placeholder-shown) + label {
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

    .register-btn {
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

    .register-btn:hover {
      background: #1E3A8A;
    }

    .links {
      margin-top: 1rem;
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

  <div class="register-container">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Register</h2>
    <form method="POST" action="" onsubmit="return validateForm()">

      <!-- Email Input -->
      <div class="floating-placeholder">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
      </div>

      <!-- Username Input -->
      <div class="floating-placeholder">
        <input type="text" name="usrname" id="usrname" placeholder=" " required>
        <label for="usrname">Username</label>
      </div>

      <!-- Password Input -->
      <div class="floating-placeholder password-container">
        <input type="password" name="passwordd" id="passwordd" placeholder=" " required minlength="8">
        <label for="passwordd">Password (Min 8 Characters)</label>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="register-btn">Register</button>

      <!-- Links -->
      <div class="links">
        <a href="index.php">Already have an account? Sign In</a>
      </div>

    </form>

    <!-- Success or Error Messages -->
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
        passwordToggle.textContent = '🙈';
      } else {
        passwordField.type = 'password';
        passwordToggle.textContent = '👁️';
      }
    }

    function validateForm() {
      const password = document.getElementById("passwordd").value;
      if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
      }
      return true;
    }
  </script>

</body>
</html>
